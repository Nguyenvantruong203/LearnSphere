import { httpClient } from '@/helpers/http'

export const couponApi = {
  async applyCoupon(code: string, orderTotal: number) {
    return await httpClient('/api/student/apply-coupon', {
      method: 'POST',
      body: { code, order_total: orderTotal },
    })
  }
}
