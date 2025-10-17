import { httpClient } from '@/helpers/http'
import type { ChatThread, ChatMessage } from '@/types/Chat'

export const chatApi = {
  async getThreads(thread_type?: 'private' | 'course_group' | 'support', course_id?: number): Promise<ChatThread[]> {
    const res = await httpClient('/api/chat/threads', {
      method: 'GET',
      params: {
        ...(thread_type ? { thread_type } : {}),
        ...(course_id ? { course_id } : {}),
      },
    })
    return (res?.threads ?? res ?? []) as ChatThread[]
  },

  async getMessages(threadId: number): Promise<{ thread: ChatThread; messages: ChatMessage[] }> {
    const res = await httpClient(`/api/chat/${threadId}/messages`, { method: 'GET' })
    return res
  },

  async sendMessage(threadId: number, message: string, messageType: string = 'text'): Promise<ChatMessage> {
    const res = await httpClient(`/api/chat/${threadId}/messages`, {
      method: 'POST',
      body: { message, message_type: messageType },
    })
    return (res as any).message
  },

  async markAsRead(threadId: number): Promise<{ success: boolean; message: string }> {
    const res = await httpClient(`/api/chat/${threadId}/read`, { method: 'POST' })
    return res
  },
}
