<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\UserCourse;
use Illuminate\Support\Facades\DB;
use App\Mail\InstructorNewEnrollmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Notification;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use App\Models\InstructorWallet;
use App\Models\Payout;
use App\Models\WalletTransaction;
use App\Events\NotificationCreated;
use App\Models\NotificationUser;

class OrderService
{
    public function createOrder($userId, $items, $amount, $couponCode = null)
    {
        return DB::transaction(function () use ($userId, $items, $amount, $couponCode) {
            $order = new Order();
            $order->user_id = $userId;
            $order->total_price = $amount;
            $order->status = 'pending_payment';

            // Xử lý coupon
            $discount = 0;
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
                if ($coupon) {
                    $discount = $coupon->type === 'percent'
                        ? $amount * $coupon->value / 100
                        : $coupon->value;
                    $order->coupon_id = $coupon->id;
                    $order->discount_amount = $discount;
                }
            }

            $order->final_price = max(0, $amount - $discount);
            $order->save();

            // Lưu order items
            foreach ($items as $courseId) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'course_id'         => $courseId,
                    'price_at_purchase' => $amount, // giả định giá cố định, anh có thể lấy từ bảng courses
                ]);
            }

            return $order;
        });
    }

    public function markOrderPaid($txnRef, $params)
    {
        return DB::transaction(function () use ($txnRef, $params) {

            Log::info('💰 [PaymentService] markOrderPaid called', ['txnRef' => $txnRef]);

            /** 1. Lấy đơn hàng */
            $order = Order::with(['items.course.instructor', 'user'])->find($txnRef);
            if (! $order) {
                Log::warning('⚠️ Order not found', ['txnRef' => $txnRef]);
                return null;
            }

            $studentId = $order->user_id;
            $student = $order->user;

            /** 2. Cập nhật trạng thái */
            $order->update(['status' => 'paid']);

            /** 3. Ghi transaction */
            Transaction::create([
                'order_id'          => $order->id,
                'amount'            => $order->final_price,
                'status'            => 'succeeded',
                'provider'          => 'VNPAY',
                'transaction_code'  => uniqid('txn_'),
                'provider_txn_id'   => $params['vnp_TransactionNo'] ?? null,
                'provider_order_id' => $params['vnp_TxnRef'] ?? null,
                'signature'         => $params['vnp_SecureHash'] ?? null,
                'raw_params'        => json_encode($params),
                'ipn_received_at'   => now(),
                'currency'          => 'USD',
            ]);

            /** 4. Ghi coupon */
            if ($order->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id'   => $studentId,
                    'order_id'  => $order->id,
                    'used_at'   => now(),
                ]);
                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            /**
             * ============================================================
             * 🟦 PHÂN BỔ FINAL_PRICE THEO TỪNG KHÓA HỌC
             * ============================================================
             */

            $totalOriginal = $order->items->sum('price_at_purchase');

            foreach ($order->items as $item) {
                $ratio = $item->price_at_purchase / max(1, $totalOriginal);

                // final_price được phân bổ theo tỷ lệ giá gốc
                $itemFinal = $order->final_price * $ratio;

                // Lưu final_price_per_item
                $item->update([
                    'final_price_per_item' => round($itemFinal, 2)
                ]);
            }


            /**
             * ============================================================
             * 🟩 GHI DANH KHÓA HỌC + CHAT + THÔNG BÁO
             * ============================================================
             */

            foreach ($order->items as $item) {
                $course = $item->course;
                $instructorId = $course->created_by ?? $course->instructor_id;

                /** Ghi danh */
                UserCourse::updateOrCreate(
                    ['user_id' => $studentId, 'course_id' => $course->id],
                    ['is_paid' => true, 'enrolled_at' => now()]
                );

                /** Tạo group chat */
                $groupThread = ChatThread::firstOrCreate(
                    ['course_id' => $course->id, 'thread_type' => 'course_group'],
                    [
                        'is_group'   => true,
                        'title'      => "Thảo luận: {$course->title}",
                        'created_by' => $instructorId,
                    ]
                );

                ChatParticipant::firstOrCreate(
                    ['thread_id' => $groupThread->id, 'user_id' => $studentId],
                    ['role' => 'student', 'joined_at' => now()]
                );

                /** Xử lý private chat */
                $consultThread = ChatThread::where([
                    'course_id'   => $course->id,
                    'thread_type' => 'consult',
                    'created_by'  => $studentId,
                ])->first();

                if ($consultThread) {
                    $consultThread->update([
                        'thread_type' => 'private',
                        'title'       => "Trao đổi với giảng viên {$course->instructor->name}",
                    ]);

                    $consultThread->participants()->syncWithoutDetaching([
                        $studentId   => ['role' => 'student'],
                        $instructorId => ['role' => 'instructor'],
                    ]);
                } else {
                    $privateThread = ChatThread::firstOrCreate(
                        ['course_id' => $course->id, 'thread_type' => 'private', 'is_group' => false],
                        ['title' => "Trao đổi với giảng viên {$course->instructor->name}", 'created_by' => $studentId]
                    );

                    $privateThread->participants()->syncWithoutDetaching([
                        $studentId   => ['role' => 'student'],
                        $instructorId => ['role' => 'instructor'],
                    ]);
                }

                /** Notify giảng viên */
                $notiInstructor = Notification::create([
                    'type'    => 'order',
                    'title'   => 'Khóa học mới được đăng ký',
                    'message' => "{$student->name} vừa đăng ký khóa học {$course->title}.",
                    'data'    => json_encode([
                        'order_id'  => $order->id,
                        'course_id' => $course->id
                    ]),
                ]);

                $notiInstructor->users()->attach([$instructorId], [
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                // 🔥 Realtime cho giảng viên
                $pivotInstructor = NotificationUser::where('notification_id', $notiInstructor->id)
                    ->where('user_id', $instructorId)
                    ->first();

                broadcast(new NotificationCreated($pivotInstructor));

                /** Notify student */
                $notiStudent = Notification::create([
                    'type'    => 'course',
                    'title'   => 'Thanh toán thành công',
                    'message' => "Bạn đã đăng ký thành công khóa học {$course->title}.",
                    'data'    => json_encode([
                        'order_id'  => $order->id,
                        'course_id' => $course->id
                    ]),
                ]);

                $notiStudent->users()->attach([$studentId], [
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                // 🔥 Realtime cho học viên
                $pivotStudent = NotificationUser::where('notification_id', $notiStudent->id)
                    ->where('user_id', $studentId)
                    ->first();

                broadcast(new NotificationCreated($pivotStudent));


                /** Mail instructor */
                if ($course->instructor?->email) {
                    Mail::to($course->instructor->email)->queue(
                        new InstructorNewEnrollmentMail($order, $course)
                    );
                }
            }


            /**
             * ============================================================
             * 🟨 CHIA DOANH THU 70/30 THEO FINAL_PRICE_PER_ITEM
             * ============================================================
             */

            foreach ($order->items as $item) {
                $course = $item->course;
                $instructor = $course->instructor;
                if (! $instructor) continue;

                $itemFinal = $item->final_price_per_item;

                $platformFee = $itemFinal * ($course->platform_fee / 100);
                $instructorAmount = $itemFinal * ($course->instructor_share / 100);

                /** Ghi payout */
                Payout::create([
                    'instructor_id'      => $instructor->id,
                    'order_item_id'      => $item->id,
                    'total_amount'       => $itemFinal,
                    'platform_fee'       => $platformFee,
                    'instructor_amount'  => $instructorAmount,
                    'status'             => 'pending',
                ]);

                /** Update instructor wallet */
                $wallet = InstructorWallet::firstOrCreate(['instructor_id' => $instructor->id]);
                $wallet->increment('balance', $instructorAmount);
                $wallet->increment('total_earned', $instructorAmount);

                WalletTransaction::create([
                    'wallet_id'   => $wallet->id,
                    'amount'      => $instructorAmount,
                    'type'        => 'credit',
                    'description' => "Doanh thu từ khóa học #{$course->id} ({$course->title})",
                    'currency'    => 'USD',
                ]);
            }

            /** 7. Notify admin */
            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

            if (!empty($adminIds)) {
                $notiAdmin = Notification::create([
                    'type'    => 'order',
                    'title'   => 'Đơn hàng mới thành công',
                    'message' => "Đơn hàng #{$order->id} đã được thanh toán thành công.",
                ]);

                $notiAdmin->users()->attach($adminIds, [
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                // 🔥 Realtime từng admin
                foreach ($adminIds as $adminId) {
                    $pivotAdmin = NotificationUser::where('notification_id', $notiAdmin->id)
                        ->where('user_id', $adminId)
                        ->first();

                    broadcast(new NotificationCreated($pivotAdmin));
                }
            }


            Log::info("✅ [PaymentService] markOrderPaid completed for order #{$order->id}");

            return $order;
        });
    }

    public function markOrderFailed($txnRef, $params)
    {
        $order = Order::where('id', $txnRef)->first();
        if ($order) {
            $order->status = 'canceled';
            $order->save();

            Transaction::create([
                'order_id'          => $order->id,
                'amount'            => $order->final_price,
                'status'            => 'failed',
                'provider'          => 'VNPAY',
                'transaction_code'  => uniqid('txn_'),
                'provider_txn_id'   => $params['vnp_TransactionNo'] ?? null,
                'provider_order_id' => $params['vnp_TxnRef'],
                'signature'         => $params['vnp_SecureHash'] ?? null,
                'raw_params'        => json_encode($params),
                'ipn_received_at'   => now(),
                'currency'          => 'USD',
            ]);
        }

        /**
         * ============================================================
         * 🔴 NOTIFY STUDENT – PAYMENT FAILED
         * ============================================================
         */
        $notiStudent = Notification::create([
            'type'    => 'order',
            'title'   => 'Thanh toán thất bại',
            'message' => "Đơn hàng #{$order->id} của bạn chưa được thanh toán thành công. Vui lòng thử lại.",
            'data'    => json_encode([
                'order_id' => $order->id,
                'user_id'  => $order->user_id,
            ]),
        ]);

        $notiStudent->users()->attach([$order->user_id], [
            'is_read' => false,
            'created_at' => now(),
        ]);

        // 🔥 Realtime cho học viên
        $pivotStudent = NotificationUser::where('notification_id', $notiStudent->id)
            ->where('user_id', $order->user_id)
            ->first();

        broadcast(new NotificationCreated($pivotStudent));


        /**
         * ============================================================
         * 🔴 NOTIFY ADMIN – LOG FAILED PAYMENT
         * ============================================================
         */
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        if (!empty($adminIds)) {

            $notiAdmin = Notification::create([
                'type'    => 'order',
                'title'   => 'Thanh toán thất bại',
                'message' => "Đơn hàng #{$order->id} vừa bị lỗi thanh toán qua VNPAY.",
                'data'    => json_encode([
                    'order_id' => $order->id,
                    'user_id'  => $order->user_id,
                ]),
            ]);

            $notiAdmin->users()->attach($adminIds, [
                'is_read' => false,
                'created_at' => now(),
            ]);

            // 🔥 Realtime từng admin
            foreach ($adminIds as $adminId) {
                $pivotAdmin = NotificationUser::where('notification_id', $notiAdmin->id)
                    ->where('user_id', $adminId)
                    ->first();

                broadcast(new NotificationCreated($pivotAdmin));
            }
        }
    }
}
