import { httpClient } from '@/helpers/http'
import type { Course, PaginationCourse, CourseSearchParams } from '@/types/Course'

export const courseApi = {
  async getAllCourses(params: CourseSearchParams): Promise<PaginationCourse<Course>> {
    return await httpClient('/api/student/courses', {
      method: 'GET',
      params,
    })
  },

  async getCourse(courseId: string): Promise<Course> {
    const response = await httpClient(`/api/student/courses/${courseId}`, {
      method: 'GET',
    })
    return response.data || response
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
    return response
  },
  async checkAccess(courseId: number) {
    const response = await httpClient(`/api/student/courses/${courseId}/check-access`, {
      method: 'GET',
    })
    return response
  },

  async getMyCourses(): Promise<Course[]> {
    const response = await httpClient('/api/student/my-courses', {
      method: 'GET',
    })
    return response.data || response
  },
  /** 🟢 Ghi danh vào khóa học (dùng cho free course) */
  async enroll(courseId: number) {
    const response = await httpClient(`/api/student/courses/${courseId}/enroll`, {
      method: 'POST',
    })
    return response.data || response
  },
}
