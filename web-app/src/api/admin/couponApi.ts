import { httpAdmin } from '@/helpers/http'
import type {
  Coupon,
  CreateCouponData,
  UpdateCouponData,
  GetCouponsParams,
  CouponsResponse,
} from '@/types/Coupon'

export const couponApi = {
  // Lấy danh sách coupons
  async getCoupons(params: GetCouponsParams = {}): Promise<CouponsResponse> {
    return httpAdmin('/api/admin/coupons', {
      method: 'GET',
      params,
      withCredentials: true,
    })
  },

  // Tạo coupon mới
  async createCoupon(
    data: CreateCouponData,
  ): Promise<{ status: string; message: string; data: Coupon }> {
    return await httpAdmin('/api/admin/coupons', {
      method: 'POST',
      body: data,
      withCredentials: true,
    })
  },

  // Cập nhật coupon
  async updateCoupon(
    id: string,
    data: UpdateCouponData,
  ): Promise<{ status: string; message: string; data: Coupon }> {
    return await httpAdmin(`/api/admin/coupons/${id}`, {
      method: 'PUT',
      body: data,
      withCredentials: true,
    })
  },

  // Xóa coupon
  async deleteCoupon(id: string): Promise<{ status: string; message: string }> {
    return await httpAdmin(`/api/admin/coupons/${id}`, {
      method: 'DELETE',
      withCredentials: true,
    })
  },
}
