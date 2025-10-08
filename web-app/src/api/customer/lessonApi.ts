import { httpClient } from '@/helpers/http'

export const lessonApi = {
  /**
   * Lấy toàn bộ topic + lessons theo courseId
   * Dùng cho sidebar bên trái (LessonList)
   */
  async getLessonListByCourseId(courseId: number) {
    return await httpClient(`/api/client/courses/${courseId}/lessons`, {
      method: 'GET',
    })
  },

  /**
   * Lấy chi tiết 1 bài học cụ thể (video, nội dung, quiz)
   * Dùng cho LessonPlayer bên phải
   */
  async getLessonDetail(lessonId: number) {
    return await httpClient(`/api/client/lessons/${lessonId}/lesson-detail`, {
      method: 'GET',
    })
  },

  
}
