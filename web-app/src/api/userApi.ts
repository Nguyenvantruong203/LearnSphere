import { http } from './http'
import type { User, PaginatedResponse } from '@/types/user'

export interface GetUsersParams {
  page?: number
  per_page?: number
  search?: string
  role?: 'student' | 'instructor' | 'admin'
  status?: 'pending' | 'approved' | 'rejected'
  sort_by?: string
  sort_order?: 'asc' | 'desc'
}

export const userApi = {
  async getUsers(params: GetUsersParams = {}): Promise<PaginatedResponse<User>> {
    // Chuyển đổi object params thành query string
    const query = new URLSearchParams(
        Object.entries(params)
            .filter(([, value]) => value !== undefined && value !== null)
            .map(([key, value]) => [key, String(value)])
    ).toString();

    return await http(`/api/users?${query}`, {
      method: 'GET',
      withCredentials: true,
    })
  },

  async updateUser(id: number, data: Partial<User>): Promise<User> {
    return await http(`/api/users/${id}`, {
        method: 'PUT',
        body: data,
        withCredentials: true,
    });
  },

  async deleteUser(id: number): Promise<void> {
    return await http(`/api/users/${id}`, {
        method: 'DELETE',
        withCredentials: true,
    });
  }
}
