import { httpClient } from '@/helpers/http'
import type {
  ApplyInstructorPayload,
  ApplyInstructorResponse,
  InstructorListResponse,
} from '@/types/User'

/**
 * 🎓 Instructor API (Public + Customer)
 */
export const instructorApi = {
  /** 🔹 Lấy danh sách giảng viên (public, có phân trang) */
  async getList(params?: {
    page?: number
    per_page?: number
    q?: string
    expertise?: string
  }): Promise<InstructorListResponse> {
    const res = await httpClient('/api/instructors', {
      method: 'GET',
      params,
    })
    return res as InstructorListResponse
  },

  /** 📝 Apply làm giảng viên (public, không cần login) */
  async apply(payload: ApplyInstructorPayload): Promise<ApplyInstructorResponse> {
    const res = await httpClient('/api/instructors/apply', {
      method: 'POST',
      body: payload,
    })
    return res as ApplyInstructorResponse
  },
}
