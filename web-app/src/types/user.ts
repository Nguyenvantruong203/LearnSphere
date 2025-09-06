/**
 * Represents a user object from the API.
 * Author: Truong
 */
export interface User {
  id: number;
  name: string;
  username?: string;
  email: string;
  email_verified_at?: string;
  phone?: string;
  address?: string;
  avatar_url?: string;
  birth_date?: string;
  gender?: 'male' | 'female' | 'other';
  role: 'student' | 'instructor' | 'admin';
  status: 'pending' | 'approved' | 'rejected';
  created_at: string;
  updated_at: string;
}

/**
 * Represents the structure of a paginated API response.
 */
export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}
