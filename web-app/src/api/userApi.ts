import { http } from '@/helpers/http'
import type { Paginator } from '@/types/Paginator'
import type { GetUsersParams, User } from '@/types/user'

export const userApi = {
async getUsers(params: GetUsersParams = {}): Promise<Paginator<User>> {
  const query = new URLSearchParams(
    Object.entries(params)
      .filter(([, value]) => value !== undefined && value !== null)
      .map(([key, value]) => [key, String(value)])
  ).toString()

  const response = await http(`/api/admin/users?${query}`, {
    method: 'GET',
    withCredentials: true
  })

  return response
},
  async updateUser(id: number, data: Partial<User>): Promise<User> {
    const response = await http(`/api/admin/users/${id}`, {
      method: 'PUT',
      body: data,
      withCredentials: true
    })
    return response.data
  },

  async deleteUser(id: number): Promise<void> {
    await http(`/api/admin/users/${id}`, {
      method: 'DELETE',
      withCredentials: true
    })
  },

  async updateProfile(data: { name: string }): Promise<User> {
    const response = await http('/api/admin/profile', {
      method: 'POST',
      body: data,
      withCredentials: true
    })
    return response.data
  },

  async updateAvatar(formData: FormData): Promise<User> {
    const response = await http('/api/admin/profile/avatar', {
      method: 'POST',
      body: formData,
      withCredentials: true
    })
    return response.data
  }
}
