import type { Course } from './Course';
import type { Lesson } from './Lesson';

export interface Topic {
    id: number;
    course_id: number;
    title: string;
    order: number;
    created_at: string;
    updated_at: string;
    course?: Course;
    lessons?: Lesson[];
}

export interface TopicPayload {
    course_id: number;
    title: string;
    order?: number;
}

export interface PaginatedTopics {
    current_page: number;
    data: Topic[];
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
