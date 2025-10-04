import { httpClient } from '@/helpers/http'
import type { Course, PaginationCourse, CourseSearchParams } from '@/types/Course'

export const customerCourseApi = {
  async searchCourses(params: CourseSearchParams): Promise<PaginationCourse<Course>> {
    return await httpClient('/api/client/courses', {
      method: 'GET',
      params,
    })
  },

  async getCourse(courseId: string): Promise<Course> {
    return await httpClient(`/api/client/courses/${courseId}`, {
      method: 'GET',
    })
  },

  async getPopularCourses(limit: number = 6): Promise<Course[]> {
    const response = await httpClient('/api/courses/popular', {
      method: 'GET',
      params: { limit },
    })
    return response.data || response
  },

  async getFeaturedCourses(limit: number = 8): Promise<Course[]> {
    const response = await httpClient('/api/courses/featured', {
      method: 'GET',
      params: { limit },
    })
    return response.data || response
  },
}
