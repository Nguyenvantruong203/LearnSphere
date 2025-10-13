import { httpAdmin } from '@/helpers/http'
import type { GetUsersParams, User, PaginationUser } from '@/types/User'

export const userApi = {
async getUsers(params: GetUsersParams = {}): Promise<PaginationUser<User>> {
  const query = new URLSearchParams(
    Object.entries(params)
      .filter(([, value]) => value !== undefined && value !== null)
      .map(([key, value]) => [key, String(value)])
  ).toString()

  const response = await httpAdmin(`/api/admin/users?${query}`, {
    method: 'GET',
    withCredentials: true
  })

  return response
},
  async updateUser(id: number, data: Partial<User>): Promise<User> {
    const response = await httpAdmin(`/api/admin/users/${id}`, {
      method: 'PUT',
      body: data,
      withCredentials: true
    })
    return response.data
  },

  async updateProfile(data: { name: string }): Promise<User> {
    const response = await httpAdmin('/api/admin/profile', {
      method: 'POST',
      body: data,
      withCredentials: true
    })
    return response
  },

  async updateAvatar(formData: FormData): Promise<User> {
    const response = await httpAdmin('/api/admin/profile/avatar', {
      method: 'POST',
      body: formData,
      withCredentials: true
    })
    return response
  }
}
