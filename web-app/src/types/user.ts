
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
