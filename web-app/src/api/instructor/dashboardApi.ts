import { httpInstructor } from '@/helpers/http'
import type {
  InstructorOverview,
  RevenueSummary,
  RevenueByMonth,
  RevenueByCourse,
  StudentSummary,
  StudentActivityPoint,
  CourseProgressItem,
  ChatStats,
} from '@/types/InstructorDashboard'

export const dashboardApi = {
  /** 📌 Overview */
  async getOverview(): Promise<InstructorOverview> {
    const response = await httpInstructor('/api/instructor/dashboard/overview', {
      method: 'GET',
    })
    return response.data
  },

  /** 📌 Revenue summary */
  async getRevenueSummary(): Promise<RevenueSummary> {
    const response = await httpInstructor('/api/instructor/dashboard/revenue/summary', {
      method: 'GET',
    })
    return response.data
  },

  /** 📊 Revenue by month (Chart) */
  async getRevenueByMonth(): Promise<RevenueByMonth[]> {
    const response = await httpInstructor('/api/instructor/dashboard/revenue/by-month', {
      method: 'GET',
    })
    return response.data
  },

  /** 📚 Revenue by course */
  async getRevenueByCourse(): Promise<RevenueByCourse[]> {
    const response = await httpInstructor('/api/instructor/dashboard/revenue/by-course', {
      method: 'GET',
    })
    return response.data
  },

  /** 👥 Student summary */
  async getStudentSummary(): Promise<StudentSummary> {
    const response = await httpInstructor('/api/instructor/dashboard/students/summary', {
      method: 'GET',
    })
    return response.data
  },

  /** 📈 Student learning activity chart */
  async getStudentActivity(): Promise<StudentActivityPoint[]> {
    const response = await httpInstructor('/api/instructor/dashboard/students/activity', {
      method: 'GET',
    })
    return response.data
  },

  /** 📘 Course progress list */
  async getCourseProgress(): Promise<CourseProgressItem[]> {
    const response = await httpInstructor('/api/instructor/dashboard/courses/progress', {
      method: 'GET',
    })
    return response.data
  },
  /** Chat Statistics */
  async getChatStats(): Promise<ChatStats> {
    const response = await httpInstructor('/api/instructor/dashboard/chat/stats', {
      method: 'GET',
    })
    return response.data
  },
}
