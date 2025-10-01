import { httpAdmin } from '@/helpers/http'
import type {
  Question,
  QuestionPayload,
  GetQuestionsParams,
  PaginationQuestion,
} from '@/types/Question'

export const topicQuestionApi = {
  /**
   * Lấy danh sách câu hỏi (public) cho quiz của topic.
   */
  async getQuestions(
    quizId: number,
    params: GetQuestionsParams = {},
  ): Promise<PaginationQuestion<Question>> {
    return await httpAdmin(`/api/admin/quizzes/${quizId}/topic-questions`, {
      method: 'GET',
      params,
      withCredentials: true,
    })
  },

  /**
   * Gợi ý câu hỏi từ lesson để chọn vào quiz topic.
   */
  async suggestQuestions(quizId: number, num: number) {
    return await httpAdmin(`/api/admin/quizzes/${quizId}/topic-questions/ai-suggest`, {
      method: 'POST',
      body: { num },
      withCredentials: true,
    })
  },

  /**
   * Lấy pool câu hỏi từ lesson trong topic (để tick chọn publish).
   */
  async getPool(quizId: number) {
    return await httpAdmin(`/api/admin/quizzes/${quizId}/topic-questions/pool`, {
      method: 'GET',
      withCredentials: true,
    })
  },

  /**
   * Publish (tick chọn) câu hỏi vào quiz topic.
   */
  async publishQuestions(quizId: number, questionIds: number[]) {
    return await httpAdmin(`/api/admin/quizzes/${quizId}/topic-questions/publish`, {
      method: 'POST',
      body: { question_ids: questionIds },
      withCredentials: true,
    })
  },
}
