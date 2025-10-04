// src/api/paymentApi.ts
import { httpClient } from '@/helpers/http'

export interface PaymentResponse {
  status: string
  url: string
  txnRef?: string
  message?: string
  errors?: any
}

export interface PaymentReturnResponse {
  status: 'success' | 'fail' | 'error'
  message: string
  code?: string
  data?: any
}

export const paymentApi = {
  async createVNPayPayment(
    amount: number,
    userId: string,
    items: number[],
    coupon?: string,
  ): Promise<PaymentResponse> {
    try {
      const response = await httpClient('/api/create-payment', {
        method: 'POST',
        body: {
          amount,
          user_id: userId,
          items,
          coupon,
        },
      })
      return response
    } catch (error: any) {
      throw new Error(error?.message || 'Có lỗi xảy ra khi tạo thanh toán VNPay')
    }
  },

  async handleVNPayReturn(params: Record<string, string>): Promise<PaymentReturnResponse> {
    try {
      // Xử lý trực tiếp từ query parameters được VNPay trả về
      const vnp_ResponseCode = params.vnp_ResponseCode
      const vnp_TxnRef = params.vnp_TxnRef
      const vnp_Amount = params.vnp_Amount
      const vnp_TransactionNo = params.vnp_TransactionNo

      if (vnp_ResponseCode === '00') {
        return {
          status: 'success',
          message: 'Thanh toán thành công!',
          data: {
            txnRef: vnp_TxnRef,
            amount: vnp_Amount ? parseInt(vnp_Amount) / 100 : 0, // VNPay trả về amount * 100
            transactionNo: vnp_TransactionNo,
          },
        }
      } else {
        // Mapping mã lỗi VNPay thành message người dùng hiểu được
        const errorMessages: Record<string, string> = {
          '07': 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
          '09': 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
          '10': 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
          '11': 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán.',
          '12': 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
          '13': 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP).',
          '24': 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
          '51': 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
          '65': 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
          '75': 'Ngân hàng thanh toán đang bảo trì.',
          '79': 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định.',
          '97': 'Chữ ký không hợp lệ',
        }

        return {
          status: 'fail',
          message: errorMessages[vnp_ResponseCode] || 'Giao dịch không thành công',
          code: vnp_ResponseCode,
          data: {
            txnRef: vnp_TxnRef,
          },
        }
      }
    } catch (error: any) {
      return {
        status: 'error',
        message: error?.message || 'Có lỗi xảy ra khi xử lý kết quả thanh toán',
      }
    }
  },
}
