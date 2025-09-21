import { http } from './http'
import type { Topic, TopicPayload } from '@/types/Topic'

export const topicApi = {
  async getTopicsByCourse(courseId: number): Promise<Topic[]> {
    const response = await http(`/api/admin/courses/${courseId}/topics`, { method: 'GET' })
    return response.data
  },

  async createTopic(payload: TopicPayload): Promise<Topic> {
    // payload phải có course_id (đã set trong formState khi mở modal)
    const courseId = payload.course_id
    if (!courseId) {
      throw new Error('course_id is required to create a topic.')
    }
    return await http(`/api/admin/courses/${courseId}/topics`, {
      method: 'POST',
      body: payload,
    })
  },

  async getTopic(id: number): Promise<Topic> {
    return await http(`/api/admin/topics/${id}`)
  },

  async updateTopic(id: number, payload: Partial<TopicPayload>): Promise<Topic> {
    return await http(`/api/admin/topics/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: payload,
    })
  },

  async deleteTopic(id: number): Promise<void> {
    await http(`/api/admin/topics/${id}`, {
      method: 'DELETE',
    })
  },
}
