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