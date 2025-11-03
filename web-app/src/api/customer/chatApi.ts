import { httpClient } from '@/helpers/http'
import type { ChatThread, ChatMessage } from '@/types/Chat'

export const chatApi = {
  /** 🔹 Lấy danh sách thread */
  async getThreads(
    thread_type?: 'private' | 'course_group' | 'support' | 'user_support' | 'consult',
    course_id?: number
  ): Promise<ChatThread[]> {
    const res = await httpClient('/api/chat/threads', {
      method: 'GET',
      params: {
        ...(thread_type ? { thread_type } : {}),
        ...(course_id ? { course_id } : {}),
      },
    })
    return (res?.threads ?? res ?? []) as ChatThread[]
  },

  /** 💬 Lấy tin nhắn trong thread */
  async getMessages(threadId: number): Promise<{ thread: ChatThread; messages: ChatMessage[] }> {
    const res = await httpClient(`/api/chat/${threadId}/messages`, { method: 'GET' })
    return res
  },

  /** ✉️ Gửi tin nhắn (text, image, file) */
  async sendMessage(
    threadId: number,
    message: string,
    messageType: string = 'text'
  ): Promise<ChatMessage> {
    const res = await httpClient(`/api/chat/${threadId}/messages`, {
      method: 'POST',
      body: { message, message_type: messageType },
    })
    return (res as any).message
  },

  /** 🧩 Chat hỗ trợ người dùng (Student ↔ Admin) */
  async startUserSupport(): Promise<{ thread: ChatThread }> {
    const res = await httpClient('/api/chat/support/user', {
      method: 'POST',
    })
    return res
  },

  /** 🧠 Chat tư vấn khóa học (Student ↔ Instructor) */
  async startConsult(courseId: number): Promise<{ thread: ChatThread }> {
    const res = await httpClient('/api/chat/consult/start', {
      method: 'POST',
      body: { course_id: courseId },
    })
    return res
  },
}
