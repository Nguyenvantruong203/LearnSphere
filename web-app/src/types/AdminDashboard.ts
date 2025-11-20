export interface AdminOverview {
  total_users: number
  total_students: number
  total_instructors: number
  today_revenue: number
  month_revenue: number
  new_orders_today: number
  new_courses: number
}

export interface RevenueMonthly {
  months: string[]
  values: number[]
}

export interface TopCourse {
  title: string
  revenue: number
}

export interface UserStatsItem {
  name: string
  value: number
}

export interface RecentOrder {
  id: number
  user: { name: string }
  created_at: string
  final_price: number
}

export interface NewUsersDailyPoint {
  dates: string[]
  values: number[]
}

export interface AdminChatStats {
  student_messages: number
  instructor_messages: number
  unread_messages: number
  active_threads: number
}

export interface SystemHealth {
  approved_courses: number
  pending_courses: number
  successful_orders: number
  total_quizzes: number
}
