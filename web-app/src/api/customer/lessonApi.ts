import { httpClient } from '@/helpers/http'

export const lessonApi = {
  /**
   * Lấy toàn bộ topic + lessons theo courseId
   * Dùng cho sidebar bên trái (LessonList)
   */
  async getLessonListByCourseId(courseId: number) {
    return await httpClient(`/api/student/courses/${courseId}/lessons`, {
      method: 'GET',
    })
  },

  /**
   * Lấy chi tiết 1 bài học cụ thể (video, nội dung, quiz)
   * Dùng cho LessonPlayer bên phải
   */
  async getLessonDetail(lessonId: number) {
    return await httpClient(`/api/student/lessons/${lessonId}/lesson-detail`, {
      method: 'GET',
    })
  },

  /** Đánh dấu hoàn thành bài học */
  async completeLesson(lessonId: number) {
    return await httpClient(`/api/student/lessons/${lessonId}/complete`, {
      method: 'POST',
    })
  },

  /**
   * 🟦 Lấy tiến độ học tập theo khóa học
   */
  async getCourseProgress(courseId: number) {
    return await httpClient(`/api/student/courses/${courseId}/progress`, {
      method: 'GET',
    })
  },
}
