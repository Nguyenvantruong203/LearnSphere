import { httpAdmin } from '@/helpers/http'
import type { Course, CoursePayload, GetCoursesParams, PaginationCourse } from '@/types/Course'
import type { Topic } from '@/types/Topic'

export const courseApi = {
  async getCourses(params: GetCoursesParams): Promise<PaginationCourse<Course>> {
    return await httpAdmin('/api/admin/courses', {
      method: 'GET',
      params
    })
  },

async createCourse(data: CoursePayload, thumbnailFile: File | null) {
  const formData = new FormData()
  Object.entries(data).forEach(([key, value]) => {
    formData.append(key, value as any)
  })
  if (thumbnailFile) {
    formData.append('thumbnail', thumbnailFile)
  }

  return await httpAdmin('/api/admin/courses', {
    method: 'POST',
    body: formData,
  })
},


  async getTopicsByCourse(courseId: number): Promise<Topic[]> {
    const response = await httpAdmin(`/api/admin/courses/${courseId}/topics`, {
      method: 'GET',
      params: { limit: 999 }
    })
    return response.data
  },

  async deleteCourse(courseId: number): Promise<void> {
    await httpAdmin(`/api/admin/courses/${courseId}`, {
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

    const response = await httpAdmin(`/api/admin/courses/${courseId}`, {
      method: 'POST',
      body: formData
    })
    return response
  }
}
