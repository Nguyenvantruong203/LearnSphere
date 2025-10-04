<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // GET /admin/coupons
    public function index(Request $request)
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($coupons);
    }

    // POST /admin/coupons
    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|unique:coupons,code',
            'type'             => 'required|in:fixed,percent',
            'value'            => 'required|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'valid_from'       => 'nullable|date',
            'valid_to'         => 'nullable|date|after_or_equal:valid_from',
            'is_active'        => 'boolean',
            'description'      => 'nullable|string',
        ]);

        $coupon = Coupon::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo mã giảm giá thành công',
            'data' => $coupon
        ], 201);
    }

    // PUT /admin/coupons/{id}
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $data = $request->validate([
            'code'             => 'required|string|unique:coupons,code,' . $coupon->id,
            'type'             => 'required|in:fixed,percent',
            'value'            => 'required|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'valid_from'       => 'nullable|date',
            'valid_to'         => 'nullable|date|after_or_equal:valid_from',
            'is_active'        => 'boolean',
            'description'      => 'nullable|string',
        ]);

        $coupon->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật mã giảm giá thành công',
            'data' => $coupon
        ]);
    }

    // DELETE /admin/coupons/{id}
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa mã giảm giá thành công'
        ]);
    }
}
