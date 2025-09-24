import { http } from '@/helpers/http'
import type { GetLessonsParams, Lesson, LessonPayload, PaginationLesson } from '@/types/Lesson'

export const lessonApi = {
  async getLessons(params: GetLessonsParams): Promise<PaginationLesson<Lesson>> {
    const { topicId, ...restParams } = params
    if (!topicId) {
      throw new Error('topicId is required for getLessons')
    }
    const response = await http(`/api/admin/topics/${topicId}/lessons`, {
      method: 'GET',
      params: restParams
    })
    return response.data
  },

  async createLesson(topicId: number, data: FormData): Promise<Lesson> {
    const response = await http(`/api/admin/topics/${topicId}/lessons/upload`, {
      method: 'POST',
      body: data
    })
    return response.data
  },

  async updateLesson(lessonId: number, data: Partial<LessonPayload>): Promise<Lesson> {
    const response = await http(`/api/admin/lessons/${lessonId}`, { method: 'PATCH', body: data })
    return response.data
  },

  async deleteLesson(lessonId: number) {
    const response = await http(`/api/admin/lessons/${lessonId}`, { method: 'DELETE' })
    return response.data
  },

  async reorderLessons(topicId: number, ids: number[]) {
    const response = await http(`/api/admin/topics/${topicId}/lessons/reorder`, {
      method: 'POST',
      body: { ids },
    })
    return response.data
  },
}
