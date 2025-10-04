<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Client áp dụng coupon
     */
public function applyCoupon(Request $request)
{
    $request->validate([
        'code' => 'required|string',
        'order_total' => 'required|numeric|min:0',
    ]);

    $userId = auth()->id();

    if (!$userId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bạn cần đăng nhập để áp dụng mã giảm giá'
        ], 401);
    }

    $coupon = Coupon::where('code', strtoupper($request->code))
        ->where('is_active', true)
        ->where(function ($q) {
            $now = now();
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })
        ->where(function ($q) {
            $now = now();
            $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
        })
        ->first();

    if (!$coupon) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
        ], 400);
    }

    if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Mã giảm giá đã hết lượt sử dụng'
        ], 400);
    }

    if ($request->order_total < $coupon->min_order_amount) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá'
        ], 400);
    }

    // Tính giảm giá
    $discount = $coupon->type === 'percent'
        ? ($request->order_total * $coupon->value / 100)
        : $coupon->value;

    $discount = min($discount, $request->order_total); // không vượt quá order_total
    $finalPrice = $request->order_total - $discount;

    return response()->json([
        'status' => 'success',
        'message' => 'Áp dụng mã giảm giá thành công',
        'data' => [
            'code' => $coupon->code,
            'discount' => $discount,
            'final_price' => $finalPrice,
        ]
    ]);
}

}
