export interface Flashcard {
  id: number
  topic_id: number
  front: string
  back: string
  image_url?: string | null
  audio_url?: string | null
  created_at?: string
  updated_at?: string
}

export interface FlashcardPayload {
  front: string
  back: string
  image_url?: string | null
  audio_url?: string | null
}
