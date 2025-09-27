
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

export interface GetUsersParams {
  page?: number
  per_page?: number
  search?: string
  role?: 'student' | 'instructor' | 'admin'
  status?: 'pending' | 'approved' | 'rejected'
  sort_by?: string
  sort_order?: 'asc' | 'desc'
}

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
