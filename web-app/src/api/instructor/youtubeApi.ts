import { httpAdmin } from '@/helpers/http'

export const youtubeApi = {
async getStatus() {
  const res = await httpAdmin('/api/instructor/me/youtube/status')
  return res  
},

  connectUrl(userId: number) {
    return `${import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000'}/api/google/connect-youtube?state=${userId}`
  },
}
