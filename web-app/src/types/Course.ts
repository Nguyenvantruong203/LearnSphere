import type { User } from './User';

export interface Course {
  id: number;
  title: string;
  slug: string;
  thumbnail_url?: string;
  short_description?: string;
  description?: string;
  status: 'draft' | 'approved' | 'archived' | 'pending' | 'rejected';
  publish_at?: string;
  price?: number;
  currency: string;
  subject?: string;
  level: 'beginner' | 'intermediate' | 'advanced';
  language: string;
  created_by: number;
  created_at: string;
  updated_at: string;
  instructor?: User; // Quan hệ với người tạo
  total_topics?: number;
  total_lessons?: number;
  total_courses?: number; 
  average_rating?: number; // avg rating
}

export type CoursePayload = Omit<Course, 'id' | 'slug' | 'created_at' | 'updated_at' | 'creator'> & {
    thumbnail_url?: string | null;
    short_description?: string | null;
    description?: string | null;
    publish_at?: string | null;
};

export interface GetCoursesParams {
  page?: number;
  limit?: number;
  search?: string;
}

export interface PaginationCourse<T> {
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

export interface CourseSearchParams {
  search?: string
  subject?: string
  level?: string
  language?: string
  is_paid?: boolean
  price_min?: number
  price_max?: number
  category_id?: number
  sort_by?: string
  sort_order?: 'asc' | 'desc'
  page?: number
  per_page?: number
}

export interface CourseSearchPayload {
  searchText: string
  filters: {
    subject: string | undefined
    level: string | undefined
    language: string | undefined
    availability: 'free' | 'paid' | undefined
  }
}

export interface MappedCourse {
  id: number
  title: string
  description?: string
  price: string
  thumbnail_url: string
  category: string
  duration: string
  progress?: string
  instructor: {
    name: string
    avatar_url: string
  }
}

export interface GetCoursesParams {
  page?: number
  limit?: number
  search?: string
  status?: string
}

export interface GetCoursesResponse {
  data: Course[]
  total: number
  current_page: number
  last_page: number
  per_page: number
}
