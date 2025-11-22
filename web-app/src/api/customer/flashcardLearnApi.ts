import { httpClient } from '@/helpers/http'
import type { Flashcard } from '@/types/Flashcard'

export const flashcardLearnApi = {

  // GET danh sách flashcards trong 1 set
  async getFlashcardsBySet(setId: number): Promise<Flashcard[]> {
    const res = await httpClient(`/api/student/flashcard-sets/${setId}/flashcards`, {
      method: 'GET'
    })
    return res.data
  },

  // Ghi log mỗi lần xem
  async logReview(flashcardId: number) {
    return httpClient(`/api/student/flashcards/${flashcardId}/review`, {
      method: 'POST'
    })
  },
}
