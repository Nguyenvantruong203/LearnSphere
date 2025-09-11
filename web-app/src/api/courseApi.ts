import { http } from './http';
import type { Course, CoursePayload } from '@/types/Course';

export interface GetCoursesParams {
    page?: number;
    limit?: number;
    search?: string;
}

export interface PaginatedCoursesResponse {
    data: Course[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const createCourseFormData = (payload: object, thumbnailFile?: File | null): FormData => {
    const formData = new FormData();
    for (const key in payload) {
        const value = (payload as any)[key];
        if (value !== null && value !== undefined) {
            formData.append(key, value);
        }
    }
    if (thumbnailFile) {
        formData.append('thumbnail', thumbnailFile);
    }
    return formData;
};


export const courseApi = {
    async getCourses(params: GetCoursesParams): Promise<PaginatedCoursesResponse> {
        const response = await http('/api/courses', {
            method: 'GET',
            params,
        });
        return response;
    },

    async createCourse(payload: CoursePayload, thumbnailFile?: File | null): Promise<Course> {
        const formData = createCourseFormData(payload, thumbnailFile);
        const response = await http('/api/courses', {
            method: 'POST',
            body: formData,
        });
        return response;
    },

    async updateCourse(slug: string, payload: Partial<CoursePayload>, thumbnailFile?: File | null): Promise<Course> {
        const formData = createCourseFormData(payload, thumbnailFile);
        formData.append('_method', 'PUT'); // Giả lập phương thức PUT

        const response = await http(`/api/courses/${slug}`, {
            method: 'POST', // Luôn dùng POST cho update có file
            body: formData,
        });
        return response;
    },

    async deleteCourse(slug: string): Promise<void> {
        await http(`/api/courses/${slug}`, {
            method: 'DELETE',
        });
    },
};
