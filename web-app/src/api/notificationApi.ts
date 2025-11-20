import { http, httpAdmin, httpInstructor } from '@/helpers/http'

/**
 * 🔔 Notification API – Dùng chung cho Student / Instructor / Admin
 * Tự động chọn http client dựa theo role đang đăng nhập
 */
export const notificationApi = {
  /**
   * 🔹 Chọn HTTP client theo role
   */
  getHttpClient() {
    const adminAuth = JSON.parse(localStorage.getItem('admin_auth') || '{}')
    const instructorAuth = JSON.parse(localStorage.getItem('instructor_auth') || '{}')
    const clientAuth = JSON.parse(localStorage.getItem('client_auth') || '{}')

    if (adminAuth?.user?.role === 'admin') return httpAdmin
    if (instructorAuth?.user?.role === 'instructor') return httpInstructor

    // Student mặc định → dùng http
    return http
  },

  /**
   * 🔹 Lấy danh sách notifications
   */
  async getNotifications(page = 1, perPage = 10) {
    const httpClient = this.getHttpClient()

    const res = await httpClient('/api/notifications', {
      method: 'GET',
      params: { page, per_page: perPage },
    })

    return {
      notifications: Array.isArray(res?.notifications) ? res.notifications : [],
      pagination: res?.pagination || { total: 0, current_page: 1, per_page: perPage },
    }
  },

  /**
   * 🔹 Đánh dấu 1 thông báo đã đọc
   */
  async markAsRead(notificationId: number) {
    const httpClient = this.getHttpClient()

    return await httpClient(`/api/notifications/${notificationId}/read`, {
      method: 'POST',
    })
  },

  /**
   * 🔹 Đánh dấu toàn bộ thông báo đã đọc
   */
  async markAllAsRead() {
    const httpClient = this.getHttpClient()

    return await httpClient(`/api/notifications/read-all`, {
      method: 'POST',
    })
  },
}
