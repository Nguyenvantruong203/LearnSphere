import { httpAdmin } from '@/helpers/http'
import type { Course, GetCoursesParams, GetCoursesResponse } from '@/types/Course'

export const approveCourseApi = {
  async getCourses(params: GetCoursesParams): Promise<GetCoursesResponse> {
    const queryParams = new URLSearchParams()

    if (params.page) queryParams.append('page', params.page.toString())
    if (params.limit) queryParams.append('limit', params.limit.toString())
    if (params.search) queryParams.append('search', params.search)
    if (params.status) queryParams.append('status', params.status)

    const url = `/api/admin/courses${queryParams.toString() ? `?${queryParams}` : ''}`
    return await httpAdmin(url, {
      method: 'GET',
      withCredentials: true,
    })
  },

  async getCourse(id: number): Promise<Course> {
    return await httpAdmin(`/api/admin/courses/${id}`, {
      method: 'GET',
      withCredentials: true,
    })
  },

  async approveCourse(id: number): Promise<{ message: string }> {
    return await httpAdmin(`/api/admin/courses/${id}/approve`, {
      method: 'POST',
      withCredentials: true,
    })
  },

  async rejectCourse(id: number, reason: string): Promise<{ message: string }> {
    return await httpAdmin(`/api/admin/courses/${id}/reject`, {
      method: 'POST',
      body: { reason },
      withCredentials: true,
    })
  },
}
