import { httpClient } from '@/helpers/http'
import type { User } from '@/types/User'

export const profileApi = {
  async updateProfile(data: User): Promise<User> {
    const response = await httpClient('/api/student/profile/update', {
      method: 'POST',
      body: data,
      withCredentials: true
    })
    return response
  },

  async updateAvatar(formData: FormData): Promise<User> {
    const response = await httpClient('/api/student/profile/avatar', {
      method: 'POST',
      body: formData,
      withCredentials: true
    })
    return response
  }
}
