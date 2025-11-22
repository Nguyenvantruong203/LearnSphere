import { httpAdmin } from '@/helpers/http'
import type { Flashcard, FlashcardPayload } from '@/types/Flashcard'

export const flashcardApi = {
  async getBySet(setId: number): Promise<Flashcard[]> {
    const res = await httpAdmin(`/api/instructor/flashcard-sets/${setId}/flashcards`, {
      method: 'GET',
    })
    return res.data
  },

  async create(setId: number, payload: FlashcardPayload): Promise<Flashcard> {
    const res = await httpAdmin(`/api/instructor/flashcard-sets/${setId}/flashcards`, {
      method: 'POST',
      body: payload,
    })
    return res.data
  },

  async update(id: number, payload: FlashcardPayload): Promise<Flashcard> {
    const res = await httpAdmin(`/api/instructor/flashcards/${id}`, {
      method: 'PUT',
      body: payload,
    })
    return res.data
  },

  async delete(id: number) {
    return httpAdmin(`/api/instructor/flashcards/${id}`, { method: 'DELETE' })
  },
}
