export interface ChatUser {
  id: number
  name: string
  avatar_url?: string
}

export interface ChatMessage {
  id: number
  thread_id: number
  sender: ChatUser
  message: string
  message_type: 'text' | 'image' | 'file' | 'system'
  sent_at: string
}

export interface ChatThread {
  id: number
  title?: string
  course?: { id: number; title: string }
  is_group: boolean
  updated_at: string
  messages?: ChatMessage[]
  participants?: { user: ChatUser }[]
}
