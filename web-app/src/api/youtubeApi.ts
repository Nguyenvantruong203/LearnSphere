import { http } from '@/helpers/http'

export const youtubeApi = {
async getStatus() {
  const res = await http('/api/admin/me/youtube/status')
  return res  
},

  connectUrl(userId: number) {
    return `${import.meta.env.VITE_API_BASE_URL}/api/google/connect-youtube?state=${userId}`
  },
}
