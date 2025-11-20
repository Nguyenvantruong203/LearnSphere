export interface InstructorOverview {
  total_courses: number
  total_students: number
  active_students: number
  wallet: {
    balance: number
    total_earned: number
    total_withdrawn: number
  }
}

export interface RevenueSummary {
  balance: number
  total_earned: number
  total_withdrawn: number
  total_payout_items: number
}

export interface RevenueByMonth {
  month: string
  total: number
}

export interface RevenueByCourse {
  title: string
  total: number
}

export interface StudentSummary {
  total_students: number
  active_students: number
  quiz_attempts: number
}

export interface StudentActivityPoint {
  date: string
  completions: number
}

export interface CourseProgressItem {
  title: string
  progress_avg: number
}

export interface ChatStats {
  sent_messages: number
  received_messages: number
  active_threads: number
  response_rate: number
  avg_response_time: number
}