<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $vnpUrl;
    protected string $returnUrl;

    public function __construct()
    {
        $this->tmnCode    = env('VNP_TMN_CODE');
        $this->hashSecret = env('VNP_HASH_SECRET');
        $this->vnpUrl     = env('VNP_URL');
        $this->returnUrl  = env('VNP_RETURN_URL');
    }

    /**
     * Build hash string for VNPay
     */
    private function buildHashData(array $data): string
    {
        ksort($data);
        $hashData = [];
        foreach ($data as $key => $value) {
            $hashData[] = urlencode($key) . "=" . urlencode($value);
        }
        return implode('&', $hashData);
    }

    /**
     * Tạo link thanh toán VNPay
     */
    public function createPayment(int $amount, string $ipAddr, int $orderId): array
    {
        $vnp_TxnRef     = (string) $orderId;
        $vnp_OrderInfo  = "Thanh toán đơn hàng #" . $orderId;
        $vnp_OrderType  = "billpayment";
        $vnp_Amount     = $amount * 100; // VNPay yêu cầu nhân 100
        $vnp_CreateDate = date('YmdHis');
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_Command"    => "pay",
            "vnp_TmnCode"    => $this->tmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $ipAddr,
            "vnp_Locale"     => "vn",
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => $vnp_OrderType,
            "vnp_ReturnUrl"  => $this->returnUrl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        // Tạo secure hash
        $hashData = $this->buildHashData($inputData);
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        $query = http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);
        $paymentUrl = $this->vnpUrl . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;

        Log::info('VNPayService Create Payment', [
            'inputData'   => $inputData,
            'hashData'    => $hashData,
            'secureHash'  => $vnpSecureHash,
            'paymentUrl'  => $paymentUrl,
        ]);

        return [
            'url'      => $paymentUrl,
            'txnRef'   => $vnp_TxnRef,
            'order_id' => $orderId,
        ];
    }

    /**
     * Xác thực chữ ký callback từ VNPay
     */
    public function validateSignature(array $inputData): bool
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        $hashString   = $this->buildHashData($inputData);
        $expectedHash = hash_hmac('sha512', $hashString, $this->hashSecret);

        $isValid = ($expectedHash === $vnp_SecureHash);

        Log::info('VNPayService Validate Signature', [
            'inputData'    => $inputData,
            'hashString'   => $hashString,
            'expectedHash' => $expectedHash,
            'vnp_SecureHash' => $vnp_SecureHash,
            'isValid'      => $isValid,
        ]);

        return $isValid;
    }
}
