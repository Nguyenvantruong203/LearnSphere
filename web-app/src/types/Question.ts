import type { Quiz } from './Quiz'

export interface QuestionOption {
  key: string   // Ví dụ: "A", "B", "C", "D"
  label: string  // Nội dung đáp án
}

export type QuestionType = 'single' | 'multi' | 'truefalse' | 'fillblank'

export interface Question {
  id: number
  quiz_id: number
  type: QuestionType
  text: string
  options?: QuestionOption[]       // Chỉ dùng cho câu hỏi có lựa chọn
  correct_options?: string[]       // Ví dụ: ["A", "C"]
  weight: number
  created_at: string
  updated_at: string
  quiz?: Quiz                      // Quan hệ ngược (optional)
}

// Payload khi tạo/cập nhật
export type QuestionPayload = Omit<
  Question,
  'id' | 'created_at' | 'updated_at' | 'quiz'
>

// Params khi query list questions
export interface GetQuestionsParams {
  page?: number
  per_page?: number
  search?: string
  type?: QuestionType
  sort_by?: string
  sort_order?: 'asc' | 'desc'
}

// Pagination response
export interface PaginationQuestion<T> {
  current_page: number
  data: T[]
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  next_page_url: string | null
  path: string
  per_page: number
  prev_page_url: string | null
  to: number
  total: number
}

// Response khi generate bằng AI
export interface GenerateQuestionsResponse {
  success: boolean
  inserted: number
  questions: Question[]
}
