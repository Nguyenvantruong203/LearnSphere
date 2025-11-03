<template>
  <LayoutLearning>
    <div class="flex h-screen overflow-y-hidden">
      <!-- Left Sidebar -->
      <div class="w-[450px] bg-white border-r overflow-y-auto">
        <LessonList :course="courseData" :topics="topics" :currentLessonId="currentLessonId" :loading="isListLoading"
          @select-lesson="handleSelectLesson" @open-quiz="handleOpenQuiz" @open-chat="isChatOpen = true" />
      </div>

      <!-- Right Content Area -->
      <div class="flex-1 bg-info bg-opacity-20 overflow-y-auto">
        <LessonPlayer v-if="activeView === 'lesson'" :lesson="currentLessonData" :lessons="lessons"
          :loading="isLessonLoading" @open-quiz="openQuiz" />
        <QuizPlayer v-if="activeView === 'quiz'" :quiz-id="currentQuizId" @exit="activeView = 'lesson'" />
      </div>

      <!-- 💬 Drawer Chat -->
      <a-drawer v-model:open="isChatOpen" width="80%" title="Thảo luận khóa học" placement="right" :mask="false"
        :closable="true">
        <div class="flex h-full">
          <!-- Danh sách thread -->
          <ChatSidebar ref="sidebarRef" :current-user="currentUser" :course-id="courseData?.id" role="student"
            @select-thread="handleSelectThread" @refresh="refreshSidebar" />

          <!-- Cửa sổ chat -->
          <div class="flex-1">
            <ChatWindow v-if="activeThread && currentUser" :key="activeThread.id" :thread-id="activeThread.id"
              :user="currentUser" />
            <div v-else class="h-full flex items-center justify-center text-gray-400 text-sm">
              Chọn một cuộc trò chuyện để bắt đầu
            </div>
          </div>
        </div>
      </a-drawer>
    </div>
  </LayoutLearning>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { notification } from 'ant-design-vue'
import LayoutLearning from '../layout/layoutLearning.vue'
import LessonList from '@/components/customer/learning/LessonList.vue'
import LessonPlayer from '@/components/customer/learning/LessonPlayer.vue'
import QuizPlayer from '@/components/customer/quiz/QuizPlayer.vue'
import ChatWindow from '@/components/common/chat/ChatWindow.vue'
import ChatSidebar from '@/components/common/chat/ChatSidebar.vue'
import { lessonApi } from '@/api/customer/lessonApi'
import type { Lesson, LessonListResponse, LessonDetailResponse } from '@/types/Lesson'
import type { Topic } from '@/types/Topic'

const route = useRoute()
const activeView = ref<'lesson' | 'quiz'>('lesson')
const currentQuizId = ref<number | null>(null)

const courseData = ref<{ id: number; title: string } | null>(null)
const topics = ref<Topic[]>([])
const lessons = ref<Lesson[]>([])
const currentLessonId = ref<number | null>(null)
const currentLessonData = ref<any>(null)
const isListLoading = ref(true)
const isLessonLoading = ref(false)
const isChatOpen = ref(false)
const activeThread = ref<any>(null)

const sidebarRef = ref()
const refreshSidebar = () => sidebarRef.value?.fetchThreads()

const clientAuth = JSON.parse(localStorage.getItem('client_auth') || '{}')
const currentUser = ref(clientAuth?.user || null)

/** ====== FETCH DANH SÁCH BÀI HỌC ====== */
const fetchLessonList = async () => {
  try {
    isListLoading.value = true
    const courseId = Number(route.params.courseId)
    if (!courseId) throw new Error('Không tìm thấy ID khóa học')

    const res: LessonListResponse = await lessonApi.getLessonListByCourseId(courseId)
    if (!res.success) throw new Error('Không thể tải danh sách bài học')

    courseData.value = res.data.course
    topics.value = res.data.topics || []

    lessons.value = topics.value.flatMap((topic: Topic) =>
      (topic.lessons || []).map((lesson: Lesson) => ({
        ...lesson,
        status: lesson.is_completed ? 'completed' : 'available',
      }))
    )

    const savedLessonId = Number(localStorage.getItem(`lastLesson_${courseId}`))
    const firstLesson = topics.value[0]?.lessons?.[0]

    if (savedLessonId) {
      await fetchLessonDetail(savedLessonId)
    } else if (firstLesson) {
      currentLessonId.value = firstLesson.id
      fetchLessonDetail(firstLesson.id)
    }
  } catch (err: any) {
    notification.error({
      message: 'Lỗi tải khóa học',
      description: err.message || 'Không thể tải danh sách bài học',
    })
  } finally {
    isListLoading.value = false
  }
}

/** ====== FETCH CHI TIẾT BÀI HỌC ====== */
const fetchLessonDetail = async (lessonId: number) => {
  try {
    isLessonLoading.value = true
    const res: LessonDetailResponse = await lessonApi.getLessonDetail(lessonId)
    if (!res.success) throw new Error('Không thể tải chi tiết bài học')

    const { lesson, course } = res.data
    currentLessonData.value = { ...lesson, course_title: course?.title || '' }
    currentLessonId.value = lessonId

    // ✅ Lưu lại bài học gần nhất
    if (courseData.value?.id)
      localStorage.setItem(`lastLesson_${courseData.value.id}`, lessonId.toString())
  } catch (err: any) {
    notification.error({
      message: 'Lỗi tải bài học',
      description: err.message || 'Không thể tải nội dung bài học',
    })
  } finally {
    isLessonLoading.value = false
  }
}

/** ====== EVENT HANDLERS ====== */
const handleSelectLesson = (lessonId: number) => fetchLessonDetail(lessonId)
const handleOpenQuiz = (quizId: number) => {
  currentQuizId.value = quizId
  activeView.value = 'quiz'
}

/** ====== AUTO LOAD ====== */
onMounted(() => {
  fetchLessonList()
})

watch(
  () => route.params.courseId,
  () => fetchLessonList()
)

const openQuiz = (quizId: number) => {
  currentQuizId.value = quizId
  activeView.value = 'quiz'
}

const handleSelectThread = (thread: any) => {
  activeThread.value = thread
}
</script>

<style scoped>
.bg-info {
  background-color: #eaf5ff;
}
</style>
