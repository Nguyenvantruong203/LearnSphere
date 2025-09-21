import { http } from './http'

export interface GetLessonsParams {
  page?: number
  limit?: number
  search?: string
  topicId?: number
}

export interface Lesson {
  id: number
  title: string
  content?: string
  video_id?: string
  video_provider?: string
  order?: number
}

export const lessonApi = {
  async getLessons(params: GetLessonsParams) {
    const { topicId, ...restParams } = params
    if (!topicId) {
      throw new Error('topicId is required for getLessons')
    }
    const response = await http(`/api/admin/topics/${topicId}/lessons`, {
      method: 'GET',
      params: restParams,
    })
    return response.data // paginator
  },

  async createLesson(topicId: number, data: FormData) {
    const response = await http(`/api/admin/topics/${topicId}/lessons/upload`, {
      method: 'POST',
      body: data,
    })
    return response.data
  },

  async updateLesson(lessonId: number, data: Partial<Lesson>) {
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
