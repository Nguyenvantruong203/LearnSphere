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
            $order = Order::with('items.course')->where('id', $txnRef)->first();
            if (!$order) return null;

            $order->status = 'paid';
            $order->save();

            // Tạo transaction
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

            // Nếu có coupon
            if ($order->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id'   => $order->user_id,
                    'order_id'  => $order->id,
                    'used_at'   => now(),
                ]);

                Coupon::where('id', $order->coupon_id)->increment('used_count');
            }

            // Gán khóa học cho user + gửi notify
            foreach ($order->items as $item) {
                UserCourse::updateOrCreate(
                    ['user_id' => $order->user_id, 'course_id' => $item->course_id],
                    ['enrolled_at' => now(), 'is_paid' => true]
                );

                $instructorId = $item->course->created_by;
                $instructor   = \App\Models\User::find($instructorId);

                // Notify DB
                \App\Models\Notification::create([
                    'type'    => 'order',
                    'title'   => 'Khóa học mới đã được đăng ký',
                    'message' => "Người dùng #{$order->user_id} vừa đăng ký khóa học {$item->course->title}",
                    'data'    => json_encode([
                        'order_id'   => $order->id,
                        'course_id'  => $item->course_id,
                        'user_id'    => $order->user_id,
                    ])
                ])->users()->attach([$instructorId]);

                // ✅ Gửi mail cho instructor
                if ($instructor && $instructor->email) {
                    Mail::to($instructor->email)->send(
                        new InstructorNewEnrollmentMail($order, $item->course)
                    );
                }
            }

            // Bắn thông báo tới Admin
            $adminIds = \App\Models\User::where('role', 'admin')->pluck('id')->toArray();
            if ($adminIds) {
                \App\Models\Notification::create([
                    'type'    => 'order',
                    'title'   => 'Đơn hàng mới',
                    'message' => "Đơn hàng #{$order->id} đã được thanh toán thành công",
                    'data'    => json_encode([
                        'order_id' => $order->id,
                        'user_id'  => $order->user_id,
                    ])
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
    }
}
