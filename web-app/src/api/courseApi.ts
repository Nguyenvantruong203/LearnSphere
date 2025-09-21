import { http } from './http'

interface CourseApi {
  getCourses(params: { page?: number; limit?: number; search?: string }): Promise<any>
  createCourse(payload: any, thumbnailFile?: File | null): Promise<any>
  getTopicsByCourse(courseId: number): Promise<any>
  deleteCourse(courseId: number): Promise<void>
  updateCourse(courseId: number, payload: any, thumbnailFile?: File | null): Promise<any>
}

export const courseApi: CourseApi = {
  async getCourses(params?: { page?: number; per_page?: number; search?: string }) {
    return await http('/api/admin/courses', {
      method: 'GET',
      params,
    })
  },

  async createCourse(payload: any, thumbnailFile?: File | null): Promise<any> {
    const formData = new FormData()
    for (const key in payload) {
      if (Object.prototype.hasOwnProperty.call(payload, key)) {
        formData.append(key, payload[key])
      }
    }
    if (thumbnailFile) {
      formData.append('thumbnail', thumbnailFile)
    }
    return await http('/api/admin/courses', {
      method: 'POST',
      body: formData,
    })
  },
  async getTopicsByCourse(courseId: number) {
    const response = await http(`/api/admin/courses/${courseId}/topics`, {
      method: 'GET',
      params: { limit: 999 },
    })
    return response.data
  },
  async deleteCourse(courseId: number): Promise<void> {
    await http(`/api/admin/courses/${courseId}`, {
      method: 'DELETE',
    })
  },
  async updateCourse(courseId: number, payload: any, thumbnailFile?: File | null): Promise<any> {
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

    return await http(`/api/admin/courses/${courseId}`, {
      method: 'POST',
      body: formData,
    })
  },
}
