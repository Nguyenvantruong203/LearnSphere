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

    return await http(`/api/admin/users?${query}`, {
      method: 'GET',
      withCredentials: true,
    })
  },

  async updateUser(id: number, data: Partial<User>): Promise<User> {
    return await http(`/api/admin/users/${id}`, {
        method: 'PUT',
        body: data,
        withCredentials: true,
    });
  },

  async deleteUser(id: number): Promise<void> {
    return await http(`/api/admin/users/${id}`, {
        method: 'DELETE',
        withCredentials: true,
    });
  },

  async updateProfile(data: { name: string }): Promise<{ data: User }> {
    return await http('/api/admin/profile', {
      method: 'POST',
      body: data,
      withCredentials: true,
    });
  },

  async updateAvatar(formData: FormData): Promise<{ data: User }> {
    return await http('/api/admin/profile/avatar', {
      method: 'POST',
      body: formData,
      withCredentials: true,
      // Xóa header Content-Type ở đây.
      // Việc này cho phép hàm http và trình duyệt tự động xử lý
      // và tạo ra header multipart/form-data đúng với boundary.
    });
  },
}
