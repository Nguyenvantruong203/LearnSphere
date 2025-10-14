import { httpClient } from '@/helpers/http'
import type { QuizSubmitPayload } from '@/types/Quiz'

export const quizApi = {
  // 🔹 Lấy chi tiết quiz (ẩn đáp án đúng)
  getQuizDetail(quizId: number) {
    return httpClient(`/api/student/quizzes/${quizId}/quiz-detail`, {
      method: 'GET',
    })
  },

  // 🔹 Bắt đầu làm quiz (tạo quiz_attempt)
  startQuizAttempt(quizId: number) {
    return httpClient(`/api/student/quizzes/${quizId}/start`, {
      method: 'POST',
    })
  },

  // 🔹 Nộp bài quiz
  submitQuizAttempt(quizId: number, payload: QuizSubmitPayload) {
    return httpClient(`/api/student/quizzes/${quizId}/submit`, {
      method: 'POST',
      body: payload,
    })
  },

  // 🔹 Xem lại kết quả / review
  getQuizReview(quizId: number, attemptId: number) {
    return httpClient(`/api/student/quizzes/${quizId}/review/${attemptId}`, {
      method: 'GET',
    })
  },
  getQuizAttempts(quizId: number) {
    return httpClient(`/api/student/quizzes/${quizId}/attempts`, {
      method: 'GET',
    })
  },
}
