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
    // Các trường có thể null hoặc không bắt buộc khi tạo/cập nhật
    thumbnail_url?: string | null;
    short_description?: string | null;
    description?: string | null;
    publish_at?: string | null;
};
