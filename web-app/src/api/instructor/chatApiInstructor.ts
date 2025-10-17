import { httpAdmin } from '@/helpers/http'
import type { ChatThread, ChatMessage } from '@/types/Chat'

export const chatApiInstructor = {
  async getThreads(
    thread_type?: 'private' | 'course_group' | 'support',
    course_id?: number
  ): Promise<ChatThread[]> {
    const res = await httpAdmin('/api/chat/threads', {
      method: 'GET',
      params: {
        ...(thread_type ? { thread_type } : {}),
        ...(course_id ? { course_id } : {}),
      },
    })

    return (res?.threads ?? res ?? []) as ChatThread[]
  },

  /**
   * 💬 Lấy danh sách tin nhắn trong thread
   */
  async getMessages(threadId: number): Promise<{ thread: ChatThread; messages: ChatMessage[] }> {
    const res = await httpAdmin(`/api/chat/${threadId}/messages`, { method: 'GET' })
    return res
  },

  /**
   * ✉️ Gửi tin nhắn (text, image, file)
   */
  async sendMessage(
    threadId: number,
    message: string,
    messageType: string = 'text'
  ): Promise<ChatMessage> {
    const res = await httpAdmin(`/api/chat/${threadId}/messages`, {
      method: 'POST',
      body: { message, message_type: messageType },
    })
    return (res as any).message
  },

  /**
   * 👁️‍🗨️ Đánh dấu thread đã đọc
   */
  async markAsRead(threadId: number): Promise<{ success: boolean; message: string }> {
    const res = await httpAdmin(`/api/chat/${threadId}/read`, { method: 'POST' })
    return res
  },

  /**
   * 👨‍🏫 Tạo hoặc mở chat riêng giữa Instructor và 1 Học viên
   * @param courseId  - ID khóa học
   * @param studentId - ID học viên cần chat
   */
  async startPrivateStudent(courseId: number, studentId: number): Promise<{ thread: ChatThread }> {
    const res = await httpAdmin(`/api/chat/course/${courseId}/student/${studentId}`, {
      method: 'POST',
    })
    return res
  },

  /**
   * 🧑‍💼 Mở chat hỗ trợ (Instructor ↔ Admin)
   * Dành cho Instructor cần hỗ trợ kỹ thuật / quản lý khóa học
   */
  async startSupport(): Promise<{ thread: ChatThread }> {
    const res = await httpAdmin(`/api/chat/support/start`, {
      method: 'POST',
    })
    return res
  },
}
