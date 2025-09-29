// User entity
export interface User {
  id: number
  name: string
  username?: string
  email: string
  email_verified_at?: string
  phone?: string
  address?: string
  avatar_url?: string
  birth_date?: string
  gender?: 'male' | 'female' | 'other'
  role: 'student' | 'instructor' | 'admin'
  status: 'pending' | 'approved' | 'rejected'
  created_at: string
  updated_at: string
}

// Query params khi get danh sách user
export interface GetUsersParams {
  page?: number
  per_page?: number
  search?: string
  role?: 'student' | 'instructor' | 'admin'
  status?: 'pending' | 'approved' | 'rejected'
  sort_by?: string
  sort_order?: 'asc' | 'desc'
}

// Pagination generic cho user
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

// Auth payloads & responses
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
