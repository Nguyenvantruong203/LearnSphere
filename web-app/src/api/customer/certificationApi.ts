import { httpClient } from '@/helpers/http'

export const certificationApi = {
  /**
   * Lấy toàn bộ chứng chỉ của user
   */
  async getMyCertificates() {
    return await httpClient('/api/student/certificates', {
      method: 'GET',
    })
  },

  async getCertificateDetail(id: number) {
    return await httpClient(`/api/student/certificates/${id}`, {
      method: 'GET',
    })
  },

  /**
   * Lấy chứng chỉ theo khóa học (khi bấm "Nhận chứng chỉ")
   */
  async getCertificateByCourse(courseId: number) {
    return await httpClient(`/api/student/certificates/by-course/${courseId}`, {
      method: 'GET',
    })
  },

  /**
   * URL tải PDF — dùng cho thẻ <a>
   */
  getCertificateDownloadUrl(certificateId) {
    const token = JSON.parse(localStorage.getItem('client_auth') || '{}')?.token

    return `${import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000'}/api/student/certificates/${certificateId}/download?token=${token}`
  },
}
