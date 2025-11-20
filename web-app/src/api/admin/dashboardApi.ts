import { httpAdmin } from '@/helpers/http'
import type {
  AdminOverview,
  RevenueMonthly,
  TopCourse,
  UserStatsItem,
  AdminChatStats,
  SystemHealth
} from '@/types/AdminDashboard'

export const adminDashboardApi = {
  /** 📌 Overview */
  async getOverview(): Promise<AdminOverview> {
    const response = await httpAdmin('/api/admin/dashboard/overview', {
      method: 'GET',
    })
    return response.data || response
  },

  /** 📊 Revenue by month (chart) */
  async getRevenueMonthly(): Promise<RevenueMonthly> {
    const response = await httpAdmin('/api/admin/dashboard/revenue/by-month', {
      method: 'GET',
    })
    return response.data || response
  },

  /** 🏆 Top course revenue */
  async getTopCourses(): Promise<TopCourse[]> {
    const response = await httpAdmin('/api/admin/dashboard/courses/top', {
      method: 'GET',
    })
    return response.courses || []
  },

  /** 👥 User stats pie chart */
  async getUserStats(): Promise<UserStatsItem[]> {
    const response = await httpAdmin('/api/admin/dashboard/users/stats', {
      method: 'GET',
    })
    return response.stats || []
  },

  /** 💬 Chat stats */
  async getChatStats(): Promise<AdminChatStats> {
    const response = await httpAdmin('/api/admin/dashboard/chat/stats', {
      method: 'GET',
    })
    return response.stats || response.data?.stats || {
      student_messages: 0,
      instructor_messages: 0,
      unread_messages: 0,
      active_threads: 0
    }
  },

  /** 🏥 System health */
  async getSystemHealth(): Promise<SystemHealth> {
    const response = await httpAdmin('/api/admin/dashboard/system/health', {
      method: 'GET',
    })
    return response.data || response || {
      approved_courses: 0,
      pending_courses: 0,
      successful_orders: 0,
      total_quizzes: 0
    }
  }
}
