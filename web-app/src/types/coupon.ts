export interface Coupon {
  id: string
  code: string
  type: 'fixed' | 'percent'
  value: number
  usage_limit?: number
  used_count: number
  min_order_amount?: number
  valid_from?: string
  valid_to?: string
  is_active: boolean
  description?: string
  created_at: string
  updated_at: string
}

export interface CreateCouponData {
  code: string
  type: 'fixed' | 'percent'
  value: number
  usage_limit?: number
  min_order_amount?: number
  valid_from?: string
  valid_to?: string
  is_active?: boolean
  description?: string
}

export interface UpdateCouponData extends CreateCouponData {}

export interface GetCouponsParams {
  page?: number
  per_page?: number
  search?: string
}

export interface CouponsResponse {
  data: Coupon[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
