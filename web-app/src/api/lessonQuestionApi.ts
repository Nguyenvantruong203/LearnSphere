import { http } from '@/helpers/http'
import type {
  Question,
  QuestionPayload,
  GetQuestionsParams,
  PaginationQuestion,
  GenerateQuestionsResponse,
} from '@/types/Question'

export const lessonQuestionApi = {
  /**
   * Lấy danh sách câu hỏi (có phân trang) cho quiz của lesson.
   */
  async getQuestions(
    quizId: number,
    params: GetQuestionsParams = {},
  ): Promise<PaginationQuestion<Question>> {
    return await http(`/api/admin/quizzes/${quizId}/lesson-questions`, {
      method: 'GET',
      params,
      withCredentials: true,
    })
  },

  /**
   * Tạo mới câu hỏi cho quiz lesson.
   */
  async createQuestion(quizId: number, data: QuestionPayload): Promise<Question> {
    return await http(`/api/admin/quizzes/${quizId}/lesson-questions`, {
      method: 'POST',
      body: { ...data, quiz_id: quizId },
      withCredentials: true,
    })
  },

  /**
   * Cập nhật câu hỏi của lesson.
   */
  async updateQuestion(
    quizId: number,
    questionId: number,
    data: Partial<QuestionPayload>,
  ): Promise<Question> {
    return await http(`/api/admin/quizzes/${quizId}/lesson-questions/${questionId}`, {
      method: 'PUT',
      body: data,
      withCredentials: true,
    })
  },

  /**
   * Sinh câu hỏi bằng AI từ quiz lesson.
   */
  async generateQuestions(quizId: number, num: number): Promise<GenerateQuestionsResponse> {
    return await http(`/api/admin/quizzes/${quizId}/lesson-questions/ai-generate`, {
      method: 'POST',
      body: { num },
      withCredentials: true,
    })
  },

  async deleteQuestion(questionId: number): Promise<void> {
    await http(`/api/admin/questions/${questionId}`, {
      method: 'DELETE',
      withCredentials: true,
    })
  },
}
