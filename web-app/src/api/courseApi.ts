import { http } from '@/helpers/http'
import type { Course, CoursePayload, GetCoursesParams, PaginationCourse } from '@/types/Course'
import type { Topic } from '@/types/Topic'

export const courseApi = {
  async getCourses(params: GetCoursesParams): Promise<PaginationCourse<Course>> {
    return await http('/api/admin/courses', {
      method: 'GET',
      params
    })
  },

  async createCourse(payload: CoursePayload, thumbnailFile?: File | null): Promise<Course> {
    const formData = new FormData()
    for (const key in payload) {
      if (Object.prototype.hasOwnProperty.call(payload, key)) {
        const value = payload[key as keyof CoursePayload]
        if (value !== undefined && value !== null) {
          formData.append(key, value as string)
        }
      }
    }
    if (thumbnailFile) {
      formData.append('thumbnail', thumbnailFile)
    }
    const response = await http('/api/admin/courses', {
      method: 'POST',
      body: formData
    })
    return response.data
  },

  async getTopicsByCourse(courseId: number): Promise<Topic[]> {
    const response = await http(`/api/admin/courses/${courseId}/topics`, {
      method: 'GET',
      params: { limit: 999 }
    })
    return response.data
  },

  async deleteCourse(courseId: number): Promise<void> {
    await http(`/api/admin/courses/${courseId}`, {
      method: 'DELETE'
    })
  },

  async updateCourse(
    courseId: number,
    payload: Partial<CoursePayload>,
    thumbnailFile?: File | null
  ): Promise<Course> {
    const formData = new FormData()

    Object.entries(payload).forEach(([key, value]) => {
      if (value !== undefined && value !== null) {
        formData.append(key, value as any)
      }
    })

    if (thumbnailFile) {
      formData.append('thumbnail', thumbnailFile)
    }

    formData.append('_method', 'PUT')

    const response = await http(`/api/admin/courses/${courseId}`, {
      method: 'POST', // Using POST for FormData with _method
      body: formData
    })
    return response.data
  }
}
