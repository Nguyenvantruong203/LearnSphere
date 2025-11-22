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
                'currency'          => 'VND',
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
                $itemFinal = $order->final_price * $ratio;

                $item->update([
                    'final_price_per_item' => round($itemFinal, 2)
                ]);
            }

            /**
             * ============================================================
             * 🟩 GHI DANH KHÓA HỌC + CHAT + NOTIFICATION
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

                /** Tạo / update group chat */
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

                /** Private chat */
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
                        $studentId    => ['role' => 'student'],
                        $instructorId => ['role' => 'instructor'],
                    ]);
                } else {

                    $privateThread = ChatThread::firstOrCreate(
                        [
                            'course_id'  => $course->id,
                            'thread_type' => 'private',
                            'is_group'   => false,
                        ],
                        [
                            'title'      => "Trao đổi với giảng viên {$course->instructor->name}",
                            'created_by' => $studentId
                        ]
                    );

                    $privateThread->participants()->syncWithoutDetaching([
                        $studentId    => ['role' => 'student'],
                        $instructorId => ['role' => 'instructor'],
                    ]);
                }


                /**
                 * ============================================================
                 * 🔔 NOTIFY GIẢNG VIÊN
                 * ============================================================
                 */

                $notiInstructor = Notification::create([
                    'type'    => 'order',
                    'title'   => 'Khóa học mới được đăng ký',
                    'message' => "{$student->name} vừa đăng ký khóa học {$course->title}.",
                    'data'    => [
                        'order_id'  => $order->id,
                        'course_id' => $course->id
                    ],
                ]);

                $notiInstructor->users()->attach($instructorId);

                $pivotInstructor = NotificationUser::where('notification_id', $notiInstructor->id)
                    ->where('user_id', $instructorId)
                    ->first();

                broadcast(new NotificationCreated($pivotInstructor));


                /**
                 * ============================================================
                 * 🔔 NOTIFY HỌC VIÊN
                 * ============================================================
                 */

                $notiStudent = Notification::create([
                    'type'    => 'course',
                    'title'   => 'Thanh toán thành công',
                    'message' => "Bạn đã đăng ký thành công khóa học {$course->title}.",
                    'data'    => [
                        'order_id'  => $order->id,
                        'course_id' => $course->id
                    ],
                ]);

                $notiStudent->users()->attach($studentId);

                $pivotStudent = NotificationUser::where('notification_id', $notiStudent->id)
                    ->where('user_id', $studentId)
                    ->first();

                broadcast(new NotificationCreated($pivotStudent));

                /**
                 * ============================================================
                 * 📧 EMAIL GIẢNG VIÊN
                 * ============================================================
                 */
                if ($course->instructor?->email) {
                    Mail::to($course->instructor->email)
                        ->queue(new InstructorNewEnrollmentMail($order, $course));
                }
            }

            /**
             * ============================================================
             * 🟨 NOTIFY ADMIN
             * ============================================================
             */

            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

            if (!empty($adminIds)) {

                $notiAdmin = Notification::create([
                    'type'    => 'order',
                    'title'   => 'Đơn hàng mới thành công',
                    'message' => "Đơn hàng #{$order->id} đã được thanh toán thành công.",
                ]);

                $notiAdmin->users()->attach($adminIds);

                foreach ($adminIds as $adminId) {

                    $pivotAdmin = NotificationUser::where('notification_id', $notiAdmin->id)
                        ->where('user_id', $adminId)
                        ->first();

                    broadcast(new NotificationCreated($pivotAdmin));
                }
            }

            /**
             * ============================================================
             * 💸 CHIA TIỀN CHO INSTRUCTOR & PLATFORM
             * ============================================================
             */

            foreach ($order->items as $item) {

                $course = $item->course;
                $instructorId = $course->created_by ?? $course->instructor_id;

                $revenueTotal = $item->final_price_per_item;  // số tiền từ khóa học này
                $share = $course->instructor_share ?? 70;      // default 70%
                $fee   = $course->platform_fee ?? 30;          // default 30%

                $instructorAmount = round($revenueTotal * ($share / 100), 2);
                $platformFee      = round($revenueTotal * ($fee / 100), 2);

                // 1) Tạo bản ghi payout
                $payout = Payout::create([
                    'instructor_id'     => $instructorId,
                    'order_item_id'     => $item->id,
                    'total_amount'      => $revenueTotal,
                    'platform_fee'      => $platformFee,
                    'instructor_amount' => $instructorAmount,
                    'status'            => 'pending',
                ]);

                // 2) Cập nhật ví của instructor
                $wallet = InstructorWallet::firstOrCreate(
                    ['instructor_id' => $instructorId],
                    [
                        'balance'         => 0,
                        'total_earned'    => 0,
                        'total_withdrawn' => 0,
                        'currency'        => 'VND',
                    ]
                );

                $wallet->credit(
                    $instructorAmount,
                    "Doanh thu từ đơn hàng #{$order->id}, khóa {$course->title}"
                );

                // 3) Nếu muốn ghi log Admin nhận được bao nhiêu (không bắt buộc)
                Log::info('💰 Revenue Share', [
                    'order'            => $order->id,
                    'course'           => $course->id,
                    'instructor_id'    => $instructorId,
                    'total'            => $revenueTotal,
                    'instructorAmount' => $instructorAmount,
                    'platformFee'      => $platformFee,
                ]);
            }

            Log::info("✅ [PaymentService] markOrderPaid completed for order #{$order->id}");

            return $order;
        });
    }


    public function markOrderFailed($txnRef, $params)
    {
        $order = Order::where('id', $txnRef)->first();
        if ($order) {
            $order->update(['status' => 'canceled']);

            Transaction::create([
                'order_id'          => $order->id,
                'amount'            => $order->final_price,
                'status'            => 'failed',
                'provider'          => 'VNPAY',
                'transaction_code'  => uniqid('txn_'),
                'provider_txn_id'   => $params['vnp_TransactionNo'] ?? null,
                'provider_order_id' => $params['vnp_TxnRef'] ?? null,
                'signature'         => $params['vnp_SecureHash'] ?? null,
                'raw_params'        => json_encode($params),
                'ipn_received_at'   => now(),
                'currency'          => 'VND',
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
            'message' => "Đơn hàng #{$order->id} chưa được thanh toán thành công. Vui lòng thử lại.",
            'data'    => [
                'order_id' => $order->id,
                'user_id'  => $order->user_id,
            ],
        ]);

        // Attach
        $notiStudent->users()->attach($order->user_id);

        // Lấy pivot NotificationUser
        $pivotStudent = NotificationUser::where('notification_id', $notiStudent->id)
            ->where('user_id', $order->user_id)
            ->first();

        // Bắn realtime đúng kiểu
        broadcast(new NotificationCreated($pivotStudent));

        /**
         * ============================================================
         * 🔴 NOTIFY ADMIN – FAILED PAYMENT
         * ============================================================
         */
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        if (!empty($adminIds)) {

            $notiAdmin = Notification::create([
                'type'    => 'order',
                'title'   => 'Thanh toán thất bại',
                'message' => "Đơn hàng #{$order->id} vừa gặp lỗi khi thanh toán qua VNPAY.",
                'data'    => [
                    'order_id' => $order->id,
                    'user_id'  => $order->user_id,
                ],
            ]);

            $notiAdmin->users()->attach($adminIds);

            foreach ($adminIds as $adminId) {

                $pivotAdmin = NotificationUser::where('notification_id', $notiAdmin->id)
                    ->where('user_id', $adminId)
                    ->first();

                broadcast(new NotificationCreated($pivotAdmin));
            }
        }
    }
}
