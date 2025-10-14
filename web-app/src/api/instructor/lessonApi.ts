import { httpAdmin } from '@/helpers/http'
import type { GetLessonsParams, Lesson, LessonPayload, PaginationLesson } from '@/types/Lesson'

export const lessonApi = {
  async getLessons({ topicId, ...params }: GetLessonsParams): Promise<PaginationLesson<Lesson>> {
    if (!topicId) throw new Error('Missing topicId')

    const { data } = await httpAdmin(`/api/instructor/topics/${topicId}/lessons`, {
      method: 'GET',
      params,
    })

    return data
  },

  async createLesson(topicId: number, data: FormData): Promise<Lesson> {
    const response = await httpAdmin(`/api/instructor/topics/${topicId}/lessons/upload`, {
      method: 'POST',
      body: data,
    })
    return response.data
  },

  async updateLesson(lessonId: number, data: Partial<LessonPayload>): Promise<Lesson> {
    const response = await httpAdmin(`/api/instructor/lessons/${lessonId}`, {
      method: 'PATCH',
      body: data,
    })
    return response.data
  },

  async deleteLesson(lessonId: number) {
    const response = await httpAdmin(`/api/instructor/lessons/${lessonId}`, { method: 'DELETE' })
    return response.data
  },

  async reorderLessons(topicId: number, ids: number[]) {
    const response = await httpAdmin(`/api/instructor/topics/${topicId}/lessons/reorder`, {
      method: 'POST',
      body: { ids },
    })
    return response.data
  },
}
