import { httpAdmin, httpInstructor } from '@/helpers/http'
import type { ChatThread, ChatMessage } from '@/types/Chat'

/**
 * ✅ Chat API dùng chung cho cả Admin & Instructor
 * Tự động chọn token và http client dựa theo user.role
 */
export const chatApiInstructor = {
  /** 🔹 Lấy danh sách thread */
  async getThreads(
    thread_type?: 'private' | 'course_group' | 'support' | 'user_support',
    course_id?: number
  ): Promise<ChatThread[]> {
    const user = JSON.parse(
      localStorage.getItem('admin_auth') ||
      localStorage.getItem('instructor_auth') ||
      '{}'
    )?.user

    const http = user?.role === 'admin' ? httpAdmin : httpInstructor

    const res = await http('/api/chat/threads', {
      method: 'GET',
      params: {
        ...(thread_type ? { thread_type } : {}),
        ...(course_id ? { course_id } : {}),
      },
    })

    return (res?.threads ?? res ?? []) as ChatThread[]
  },

  /** 🔹 Lấy danh sách tin nhắn */
  async getMessages(threadId: number): Promise<{ thread: ChatThread; messages: ChatMessage[] }> {
    const user = JSON.parse(
      localStorage.getItem('admin_auth') ||
      localStorage.getItem('instructor_auth') ||
      '{}'
    )?.user

    const http = user?.role === 'admin' ? httpAdmin : httpInstructor
    const res = await http(`/api/chat/${threadId}/messages`, { method: 'GET' })
    return res
  },

  /** 🔹 Gửi tin nhắn */
  async sendMessage(threadId: number, message: string, messageType = 'text'): Promise<ChatMessage> {
    const user = JSON.parse(
      localStorage.getItem('admin_auth') ||
      localStorage.getItem('instructor_auth') ||
      '{}'
    )?.user

    const http = user?.role === 'admin' ? httpAdmin : httpInstructor
    const res = await http(`/api/chat/${threadId}/messages`, {
      method: 'POST',
      body: { message, message_type: messageType },
    })
    return (res as any).message
  },
}
