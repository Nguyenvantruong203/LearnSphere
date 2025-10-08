import { httpClient } from '@/helpers/http'
import type {
  QuizDetailResponse,
  QuizStartResponse,
  QuizSubmitPayload,
  QuizSubmitResponse,
  QuizReviewResponse,
} from '@/types/Quiz'

export const quizApi = {
  // 🔹 Lấy chi tiết quiz (ẩn đáp án đúng)
  getQuizDetail(quizId: number) {
    return httpClient(`/api/client/quizzes/${quizId}/quiz-detail`, {
      method: 'GET',
    }) as Promise<QuizDetailResponse>
  },

  // 🔹 Bắt đầu làm quiz (tạo quiz_attempt)
  startQuizAttempt(quizId: number) {
    return httpClient(`/api/client/quizzes/${quizId}/start`, {
      method: 'POST',
    }) as Promise<QuizStartResponse>
  },

  // 🔹 Nộp bài quiz
  submitQuizAttempt(quizId: number, payload: QuizSubmitPayload) {
    return httpClient(`/api/client/quizzes/${quizId}/submit`, {
      method: 'POST',
      body: payload,
    }) as Promise<QuizSubmitResponse>
  },

  // 🔹 Xem lại kết quả / review
  getQuizReview(quizId: number) {
    return httpClient(`/api/client/quizzes/${quizId}/review`, {
      method: 'GET',
    }) as Promise<QuizReviewResponse>
  },
}
