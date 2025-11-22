<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\VNPayService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    protected VNPayService $vnpay;
    protected OrderService $orderService;

    public function __construct(VNPayService $vnpay, OrderService $orderService)
    {
        $this->vnpay = $vnpay;
        $this->orderService = $orderService;
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'   => 'required|numeric|min:10|max:500000000',
            'user_id'  => 'required|exists:users,id',
            'items'    => 'required|array', // danh sách course_id
            'coupon'   => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => $validator->errors()
            ], 400);
        }

        // Tạo order trước
        $order = $this->orderService->createOrder(
            $request->user_id,
            $request->items,
            $request->amount,
            $request->coupon
        );

        // Sinh link VNPay
        $result = $this->vnpay->createPayment((int)$order->final_price, $request->ip(), $order->id);

        return response()->json([
            'status'   => 'success',
            'url'      => $result['url'],
            'txnRef'   => $result['txnRef'],
            'order_id' => $order->id
        ]);
    }

    /**
     * Người dùng quay lại frontend (success/fail)
     */
    public function vnpayReturn(Request $request)
    {
        $params = $request->all();
        Log::info('VNPay Return', $params);

        if ($this->vnpay->validateSignature($params)) {
            return redirect()->to(env('APP_FRONTEND_URL') . '/payment/vnpay-return?' . http_build_query($params));
        }

        return redirect()->to(env('APP_FRONTEND_URL') . '/payment/vnpay-return?vnp_ResponseCode=97&error=invalid-hash');
    }

    /**
     * VNPay gọi ngầm (IPN)
     */
    public function vnpayIpn(Request $request)
    {
        Log::info('VNPay IPN Callback', ['method' => $request->method(), 'params' => $request->all()]);
        $params = $request->all();
        Log::info('VNPay IPN Callback', $params);

        // Kiểm tra chữ ký
        if (!$this->vnpay->validateSignature($params)) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $orderId = $params['vnp_TxnRef'] ?? null;
        $responseCode = $params['vnp_ResponseCode'] ?? null;
        $transactionStatus = $params['vnp_TransactionStatus'] ?? null;

        if ($responseCode === '00' && $transactionStatus === '00') {
            // ✅ Thanh toán thành công
            $this->orderService->markOrderPaid($orderId, $params);
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        // ❌ Thanh toán thất bại
        $this->orderService->markOrderFailed($orderId, $params);
        return response()->json(['RspCode' => '00', 'Message' => 'Payment Failed']);
    }
}
