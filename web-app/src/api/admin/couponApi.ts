import { http } from '@/helpers/http'
import type { 
  Coupon, 
  CreateCouponData, 
  UpdateCouponData, 
  GetCouponsParams, 
  CouponsResponse 
} from '@/types/Coupon'

export const couponApi = {
  // Lấy danh sách coupons
  async getCoupons(params: GetCouponsParams = {}): Promise<CouponsResponse> {
    const queryParams = new URLSearchParams()
    if (params.page) queryParams.append('page', params.page.toString())
    if (params.per_page) queryParams.append('per_page', params.per_page.toString())
    if (params.search) queryParams.append('search', params.search)

    const queryString = queryParams.toString()
    const url = `/api/admin/coupons${queryString ? `?${queryString}` : ''}`
    
    return await http(url, {
      method: 'GET',
      withCredentials: true
    })
  },

  // Tạo coupon mới
  async createCoupon(data: CreateCouponData): Promise<{ status: string; message: string; data: Coupon }> {
    return await http('/api/admin/coupons', {
      method: 'POST',
      body: data,
      withCredentials: true
    })
  },

  // Cập nhật coupon
  async updateCoupon(id: string, data: UpdateCouponData): Promise<{ status: string; message: string; data: Coupon }> {
    return await http(`/api/admin/coupons/${id}`, {
      method: 'PUT',
      body: data,
      withCredentials: true
    })
  },

  // Xóa coupon
  async deleteCoupon(id: string): Promise<{ status: string; message: string }> {
    return await http(`/api/admin/coupons/${id}`, {
      method: 'DELETE',
      withCredentials: true
    })
  }
}
