/**
 * ================================
 * 👤 USER TYPES (Shared Entity)
 * ================================
 */

// User entity (bao gồm cả instructor fields)
export interface User {
  id: number
  name: string
  username?: string
  email: string
  email_verified_at?: string | null
  phone?: string | null
  address?: string | null
  avatar_url?: string | null
  birth_date?: string | null
  gender?: 'male' | 'female' | 'other' | null
  role: 'student' | 'instructor' | 'admin'
  status: 'pending' | 'approved' | 'rejected'
  expertise?: string | null
  bio?: string | null
  linkedin_url?: string | null
  portfolio_url?: string | null
  teaching_experience?: number | null
  created_at: string
  updated_at: string
}

/**
 * ================================
 * 🔍 GET USERS PARAMS (Filters)
 * ================================
 */
export interface GetUsersParams {
  page?: number
  per_page?: number
  search?: string
  role?: 'student' | 'instructor' | 'admin'
  status?: 'pending' | 'approved' | 'rejected'
  sort_by?: string
  sort_order?: 'asc' | 'desc'
}

/**
 * ================================
 * 📄 PAGINATION GENERIC
 * ================================
 */
export interface PaginationUser<T> {
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

/**
 * ================================
 * 🔐 AUTH PAYLOADS & RESPONSES
 * ================================
 */
export type RegisterPayload = {
  username: string
  email: string
  password: string
  password_confirmation: string
}

export type LoginPayload = {
  email: string
  password: string
}

export type LoginResponse = {
  user: User
  access_token: string
  token_type: string
  message?: string
}

export type RegisterResponse = {
  message: string
}

/**
 * ================================
 * 🎓 INSTRUCTOR MODULE
 * ================================
 */

// Payload apply instructor
export interface ApplyInstructorPayload {
  name: string
  email: string
  phone?: string
  password?: string
  expertise: string
  bio: string
  linkedin_url?: string
  portfolio_url?: string
  teaching_experience?: number
}

// Response khi apply instructor
export interface ApplyInstructorResponse {
  success: boolean
  message: string
  data?: User
}

// Response khi lấy danh sách instructor
export interface InstructorListResponse {
  success: boolean
  data: PaginationUser<User>
}
