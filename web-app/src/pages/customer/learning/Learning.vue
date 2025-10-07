<template>
  <LayoutLearning>
    <div class="flex h-screen overflow-y-hidden">
      <!-- Left Sidebar -->
      <div class="w-[350px] bg-white border-r overflow-y-auto">
        <LessonList :course="courseData" :topics="topics" :currentLessonId="currentLessonId" :loading="isListLoading"
          @select-lesson="handleSelectLesson" @open-quiz="goToQuiz" />
      </div>

      <!-- Right Content Area -->
      <div class="flex-1 bg-info bg-opacity-20 overflow-y-auto">
        <LessonPlayer :lesson="currentLessonData" :lessons="lessons" :loading="isLessonLoading" @open-quiz="goToQuiz" />

      </div>
    </div>
  </LayoutLearning>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { notification } from 'ant-design-vue'

import LayoutLearning from '../layout/layoutLearning.vue'
import LessonList from '@/components/customer/learning/LessonList.vue'
import LessonPlayer from '@/components/customer/learning/LessonPlayer.vue'
import { lessonApi } from '@/api/customer/lessonApi'

import type { Lesson, LessonListResponse, LessonDetailResponse } from '@/types/Lesson'
import type { Topic } from '@/types/Topic'

const route = useRoute()
const router = useRouter()

// ===== STATE =====
const courseData = ref<{ id: number; title: string } | null>(null)
const topics = ref<Topic[]>([])
const lessons = ref<Lesson[]>([])
const currentLessonId = ref<number | null>(null)
const currentLessonData = ref<any>(null)
const isListLoading = ref(true)
const isLessonLoading = ref(false)

// ===== FETCH DANH SÁCH BÀI HỌC =====
const fetchLessonList = async () => {
  try {
    isListLoading.value = true
    const courseId = Number(route.params.courseId)
    if (!courseId) throw new Error('Không tìm thấy ID khóa học')

    const res: LessonListResponse = await lessonApi.getLessonListByCourseId(courseId)
    if (!res.success) throw new Error('Không thể tải danh sách bài học')

    courseData.value = res.data.course
    topics.value = res.data.topics || []

    // Flatten để dùng cho Player / điều hướng
    lessons.value = topics.value.flatMap((topic: Topic) =>
      (topic.lessons || []).map((lesson: Lesson) => ({
        ...lesson,
        status: lesson.is_completed ? 'completed' : 'available'
      }))
    )

    // Lấy lesson ID từ localStorage hoặc bài đầu tiên
    const savedLessonId = Number(localStorage.getItem(`lastLesson_${courseId}`))
    const firstLesson = topics.value[0]?.lessons?.[0]

    if (savedLessonId) {
      await fetchLessonDetail(savedLessonId)
    } else if (firstLesson) {
      currentLessonId.value = firstLesson.id
      fetchLessonDetail(firstLesson.id) // không await để UI hiển thị nhanh hơn
    }
  } catch (err: any) {
    console.error('fetchLessonList error:', err)
    notification.error({ message: 'Lỗi tải khóa học', description: err.message || 'Không thể tải danh sách bài học' })
  } finally {
    isListLoading.value = false
  }
}

// ===== FETCH CHI TIẾT BÀI HỌC =====
const fetchLessonDetail = async (lessonId: number) => {
  try {
    isLessonLoading.value = true
    const res: LessonDetailResponse = await lessonApi.getLessonDetail(lessonId)
    if (!res.success) throw new Error('Không thể tải chi tiết bài học')

    const { lesson, course } = res.data
    currentLessonData.value = {
      ...lesson,
      course_title: course?.title || '',
    }
    currentLessonId.value = lessonId

    // ✅ Lưu lại bài học gần nhất
    if (courseData.value?.id)
      localStorage.setItem(`lastLesson_${courseData.value.id}`, lessonId.toString())
  } catch (err: any) {
    console.error('fetchLessonDetail error:', err)
    notification.error({ message: 'Lỗi tải bài học', description: err.message || 'Không thể tải nội dung bài học' })
  } finally {
    isLessonLoading.value = false
  }
}

// ===== EVENT HANDLERS =====
const handleSelectLesson = (lessonId: number) => {
  fetchLessonDetail(lessonId)
}

const goToQuiz = (quizId: number) => {
  router.push(`/quiz/${quizId}`)
}

// ===== AUTO LOAD =====
onMounted(() => {
  fetchLessonList()
})

// ===== RELOAD KHI ĐỔI KHÓA HỌC =====
watch(
  () => route.params.courseId,
  () => {
    fetchLessonList()
  }
)
</script>

<style scoped>
.bg-info {
  background-color: #eaf5ff;
}
</style>
