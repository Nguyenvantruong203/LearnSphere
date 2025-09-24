import { http } from '@/helpers/http'
import type { Quiz, QuizPayload } from '@/types/Quiz'

export const quizApi = {
  /**
   * Lấy danh sách quiz của 1 lesson.
   */
  async getQuizzesByLesson(lessonId: number): Promise<Quiz[]> {
    const response = await http(`/api/admin/lessons/${lessonId}/quizzes`, {
      method: 'GET',
    })
    return response.data
  },

  /**
   * Lấy danh sách quiz của 1 topic (quiz tổng hợp cuối chương).
   */
  async getQuizzesByTopic(topicId: number): Promise<Quiz[]> {
    const response = await http(`/api/admin/topics/${topicId}/quizzes`, {
      method: 'GET',
    })
    return response.data
  },

  /**
   * Tạo quiz gắn với 1 lesson.
   */
  async createQuizForLesson(lessonId: number, payload: QuizPayload): Promise<Quiz> {
    return await http(`/api/admin/lessons/${lessonId}/quizzes`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: payload,
    })
  },

  /**
   * Tạo quiz gắn với 1 topic.
   */
  async createQuizForTopic(topicId: number, payload: QuizPayload): Promise<Quiz> {
    return await http(`/api/admin/topics/${topicId}/quizzes`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: payload,
    })
  },

  /**
   * Lấy chi tiết quiz.
   */
  async getQuiz(id: number): Promise<Quiz> {
    const response = await http(`/api/admin/quizzes/${id}`, { method: 'GET' })
    return response.data
  },

  /**
   * Cập nhật quiz.
   */
  async updateQuiz(id: number, payload: Partial<QuizPayload>): Promise<Quiz> {
    return await http(`/api/admin/quizzes/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: payload,
    })
  },

  /**
   * Xoá quiz.
   */
  async deleteQuiz(id: number): Promise<void> {
    await http(`/api/admin/quizzes/${id}`, {
      method: 'DELETE',
    })
  },
}
