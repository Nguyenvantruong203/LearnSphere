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
            Log::info('MARK ORDER PAID CALLED', ['txnRef' => $txnRef]);

            $order = Order::with('items.course')->where('id', $txnRef)->first();
            if (!$order) {
                Log::warning('ORDER NOT FOUND', ['txnRef' => $txnRef]);
                return null;
            }

            $order->status = 'paid';
            $order->save();

            // 🔹 Ghi nhận giao dịch thanh toán
            Transaction::create([
                'order_id'          => $order->id,
                'amount'            => $order->final_price,
                'status'            => 'succeeded',
                'provider'          => 'VNPAY',
                'transaction_code'  => uniqid('txn_'),
                'provider_txn_id'   => $params['vnp_TransactionNo'] ?? null,
                'provider_order_id' => $params['vnp_TxnRef'],
                'signature'         => $params['vnp_SecureHash'] ?? null,
                'raw_params'        => json_encode($params),
                'ipn_received_at'   => now(),
                'currency'          => 'VND',
            ]);

            // 🔹 Nếu có coupon
            if ($order->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id'   => $order->user_id,
                    'order_id'  => $order->id,
                    'used_at'   => now(),
                ]);

                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            // 🔹 Gán khóa học cho user + join chat + gửi notify
            foreach ($order->items as $item) {
                $course = $item->course;
                $studentId = $order->user_id;
                $instructorId = $course->created_by;

                // ✅ Ghi danh user vào khóa học
                UserCourse::updateOrCreate(
                    ['user_id' => $studentId, 'course_id' => $course->id],
                    ['enrolled_at' => now(), 'is_paid' => true]
                );

                /**
                 * ==================================================
                 * 🔸 PHẦN MỚI: TẠO HOẶC THÊM CHAT GROUP + CHAT PRIVATE
                 * ==================================================
                 */

                // 🧩 (1) Chat nhóm khóa học
                $groupThread = ChatThread::firstOrCreate(
                    [
                        'course_id'   => $course->id,
                        'thread_type' => 'course_group',
                    ],
                    [
                        'is_group'   => true,
                        'title'      => $course->title,
                        'created_by' => $instructorId,
                    ]
                );

                ChatParticipant::firstOrCreate(
                    ['thread_id' => $groupThread->id, 'user_id' => $studentId],
                    ['role' => 'student', 'joined_at' => now()]
                );

                Log::info("✅ User {$studentId} joined group chat {$groupThread->id} for course {$course->id}");

                // 🧩 (2) Chat riêng với giảng viên
                $privateThread = ChatThread::firstOrCreate(
                    [
                        'course_id'   => $course->id,
                        'thread_type' => 'private',
                        'is_group'    => false,
                    ],
                    [
                        'title'      => "Trao đổi với giảng viên {$course->instructor->name}",
                        'created_by' => $studentId,
                    ]
                );

                // Thêm học viên + giảng viên vào thread
                ChatParticipant::firstOrCreate(
                    ['thread_id' => $privateThread->id, 'user_id' => $studentId],
                    ['role' => 'student', 'joined_at' => now()]
                );

                ChatParticipant::firstOrCreate(
                    ['thread_id' => $privateThread->id, 'user_id' => $instructorId],
                    ['role' => 'instructor', 'joined_at' => now()]
                );

                Log::info("✅ Created/Linked private chat between student {$studentId} and instructor {$instructorId}");

                /**
                 * ==================================================
                 * 🔔 Gửi notification & email như cũ
                 * ==================================================
                 */

                // 🔔 Notify instructor
                Notification::create([
                    'type'    => 'order',
                    'title'   => 'Khóa học mới đã được đăng ký',
                    'message' => "Người dùng #{$studentId} vừa đăng ký khóa học {$course->title}",
                    'data'    => json_encode([
                        'order_id'   => $order->id,
                        'course_id'  => $course->id,
                        'user_id'    => $studentId,
                    ]),
                ])->users()->attach([$instructorId]);

                // 🔔 Notify student
                Notification::create([
                    'type'    => 'course',
                    'title'   => 'Thanh toán thành công',
                    'message' => "Bạn đã đăng ký thành công khóa học {$course->title}",
                    'data'    => json_encode([
                        'order_id'  => $order->id,
                        'course_id' => $course->id,
                    ]),
                ])->users()->attach([$studentId]);

                // 📧 Gửi mail cho instructor
                $instructor = User::find($instructorId);
                if ($instructor && $instructor->email) {
                    Mail::to($instructor->email)->send(
                        new InstructorNewEnrollmentMail($order, $course)
                    );
                }
            }

            // 🔔 Notify admin
            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
            if ($adminIds) {
                Notification::create([
                    'type'    => 'order',
                    'title'   => 'Đơn hàng mới',
                    'message' => "Đơn hàng #{$order->id} đã được thanh toán thành công",
                    'data'    => json_encode([
                        'order_id' => $order->id,
                        'user_id'  => $order->user_id,
                    ]),
                ])->users()->attach($adminIds);
            }

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
                'currency'          => 'VND',
            ]);
        }
        // ⚠️ Notify student
        Notification::create([
            'type'    => 'order',
            'title'   => 'Thanh toán thất bại',
            'message' => "Đơn hàng #{$order->id} của bạn chưa được thanh toán thành công. Vui lòng thử lại.",
            'data'    => json_encode([
                'order_id' => $order->id,
                'user_id'  => $order->user_id,
            ]),
        ])->users()->attach([$order->user_id]);

        // ⚠️ Notify admin (để theo dõi lỗi)
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if ($adminIds) {
            Notification::create([
                'type'    => 'order',
                'title'   => 'Thanh toán thất bại',
                'message' => "Đơn hàng #{$order->id} vừa bị lỗi thanh toán qua VNPAY.",
                'data'    => json_encode([
                    'order_id' => $order->id,
                    'user_id'  => $order->user_id,
                ]),
            ])->users()->attach($adminIds);
        }
    }
}
