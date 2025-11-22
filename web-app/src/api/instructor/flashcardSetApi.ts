import { httpAdmin } from '@/helpers/http'

export const flashcardSetApi = {
  async getSets(topicId: number) {
    const res = await httpAdmin(`/api/instructor/topics/${topicId}/flashcard-sets`, { method: "GET" })
    return res.data
  },

  async createSet(topicId: number, payload: any) {
    const res = await httpAdmin(`/api/instructor/topics/${topicId}/flashcard-sets`, {
      method: "POST",
      body: payload,
    })
    return res.data
  },

  async updateSet(setId: number, payload: any) {
    const res = await httpAdmin(`/api/instructor/flashcard-sets/${setId}`, {
      method: "PUT",
      body: payload,
    })
    return res.data
  },

  async deleteSet(setId: number) {
    return httpAdmin(`/api/instructor/flashcard-sets/${setId}`, { method: "DELETE" })
  },
}
