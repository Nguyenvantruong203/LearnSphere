import type { User } from './user';

export interface Course {
  id: number;
  title: string;
  slug: string;
  thumbnail_url?: string;
  short_description?: string;
  description?: string;
  status: 'draft' | 'published' | 'archived';
  visibility: 'public' | 'unlisted' | 'private';
  publish_at?: string;
  price: number;
  currency: string;
  level: 'beginner' | 'intermediate' | 'advanced';
  language: string;
  created_by: number;
  created_at: string;
  updated_at: string;
  creator?: User; // Quan hệ với người tạo
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
