import { http } from './http';
import type { PaginatedTopics, Topic, TopicPayload } from '@/types/Topic';

export const topicApi = {
    async getTopics(params: { page?: number; search?: string; course_id?: number } = {}): Promise<PaginatedTopics> {
        return await http('/api/topics', { params });
    },

    async createTopic(payload: TopicPayload): Promise<Topic> {
        return await http('/api/topics', {
            method: 'POST',
            body: payload,
        });
    },

    async getTopic(id: number): Promise<Topic> {
        return await http(`/api/topics/${id}`);
    },

    async updateTopic(id: number, payload: Partial<TopicPayload>): Promise<Topic> {
        return await http(`/api/topics/${id}`, {
            method: 'PUT',
            body: payload,
        });
    },

    async deleteTopic(id: number): Promise<void> {
        await http(`/api/topics/${id}`, {
            method: 'DELETE',
        });
    },
};
