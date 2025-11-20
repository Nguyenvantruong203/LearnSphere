import type { Topic } from './Topic';
import type { Quiz } from './Quiz';

export interface Lesson {
  id: number;
  topic_id: number;
  title: string;
  video_provider: 'youtube' | 'cloudinary' | 'vimeo';
  video_id?: string;
  video_url?: string;
  content?: string;
  order: number;
  duration_seconds?: number;
  duration_minutes?: number;
  player_params?: Record<string, any>;
  created_at: string;
  updated_at: string;
  topic?: Topic;
  quiz?: Quiz;
  is_completed?: boolean;
  status?: 'completed' | 'in-progress' | 'available' | 'locked';
}

/**
 * Represents the payload for creating or updating a lesson.
 */
export type LessonPayload = Omit<Lesson, 'id' | 'created_at' | 'updated_at' | 'topic'>;

export interface GetLessonsParams {
  page?: number;
  limit?: number;
  search?: string;
  topicId?: number;
}

export interface PaginationLesson<T> {
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

export interface LessonListResponse {
  success: boolean;
  data: {
    course: {
      id: number;
      title: string;
    };
    topics: Topic[];
  };
}

export interface LessonDetailResponse {
  success: boolean;
  data: {
    id: number;
    title: string;
    video_provider: string;
    video_id?: string;
    video_url?: string;
    content?: string;
    duration_seconds?: number;
    quiz?: Quiz;
    course: {
      id: number;
      title: string;
    };
  };
}
