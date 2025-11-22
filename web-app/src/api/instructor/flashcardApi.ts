import { httpAdmin } from '@/helpers/http'
import type { Flashcard, FlashcardPayload } from '@/types/Flashcard'

export const flashcardApi = {
  async getFlashcards(topicId: number): Promise<Flashcard[]> {
    const { data } = await httpAdmin(`/api/instructor/topics/${topicId}/flashcards`, {
      method: 'GET',
    })
    return data
  },

  async createFlashcard(topicId: number, payload: FlashcardPayload): Promise<Flashcard> {
    const res = await httpAdmin(`/api/instructor/topics/${topicId}/flashcards`, {
      method: 'POST',
      body: payload,
    })
    return res.data
  },

  async updateFlashcard(id: number, payload: Partial<FlashcardPayload>): Promise<Flashcard> {
    const res = await httpAdmin(`/api/instructor/flashcards/${id}`, {
      method: 'PUT',
      body: payload,
    })
    return res.data
  },

  async deleteFlashcard(id: number) {
    return httpAdmin(`/api/instructor/flashcards/${id}`, { method: 'DELETE' })
  },
}
