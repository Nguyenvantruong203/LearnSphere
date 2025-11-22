import { httpClient } from '@/helpers/http'

export const reviewApi = {
  // 🔹 Lấy danh sách review của khóa học
  getCourseReviews(courseId: string | number, page = 1, limit = 10) {
    return httpClient(`/api/student/reviews/course/${courseId}?page=${page}&limit=${limit}`, {
      method: 'GET',
    })
  },
  async canReview(courseId: number | string) {
    return httpClient(`/api/student/reviews/course/${courseId}/can-review`, {
      method: 'GET',
    })
  },
  // 🔹 Tạo review
  createReview(courseId: string | number, payload: { rating: number; comment?: string }) {
    return httpClient(`/api/student/reviews/course/${courseId}`, {
      method: 'POST',
      body: payload,
    })
  },

  // 🔹 Cập nhật review
  updateReview(id: string | number, payload: { rating: number; comment?: string }) {
    return httpClient(`/api/student/reviews/${id}`, {
      method: 'PUT',
      body: payload,
    })
  },

  // 🔹 Xóa review
  deleteReview(id: string | number) {
    return httpClient(`/api/student/reviews/${id}`, {
      method: 'DELETE',
    })
  },

  // 🔹 Summary rating (avg + star breakdown)
  getReviewSummary(courseId: string | number) {
    return httpClient(`/api/student/reviews/summary/${courseId}`, {
      method: 'GET',
    })
  },
}
