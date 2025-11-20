<template>
  <LayoutLearning>
    <div class="flex h-screen overflow-y-hidden">

      <div class="w-[450px] bg-white border-r overflow-y-auto">
        <LessonList :course="courseData" :topics="topics" :currentLessonId="currentLessonId" :loading="isListLoading"
          :progress="progress" @select-lesson="handleSelectLesson" @open-quiz="handleOpenQuiz"
          @open-chat="isChatOpen = true" @open-certificate="openCertificate"/>
      </div>

      <div class="flex-1 bg-info bg-opacity-20 overflow-y-auto">

        <LessonPlayer v-if="activeView === 'lesson'" :lesson="currentLessonData" :lessons="lessons"
          :loading="isLessonLoading" @open-quiz="openQuiz" @lesson-completed="handleLessonCompleted" />

        <QuizPlayer v-if="activeView === 'quiz'" :quiz-id="currentQuizId" @exit="activeView = 'lesson'" />
      </div>

      <!-- Chat Drawer -->
      <a-drawer v-model:open="isChatOpen" width="80%" title="Thảo luận khóa học" placement="right" :mask="false"
        :closable="true">
        <div class="flex h-full">

          <!-- Sidebar -->
          <ChatSidebar ref="sidebarRef" :current-user="currentUser" :course-id="courseData?.id" role="student"
            @select-thread="handleSelectThread" @refresh="refreshSidebar" />

          <!-- Chat window -->
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

    <CertificateModal v-model:open="isCertificateModalOpen" :certificate="certificateData" />
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
import { certificationApi } from '@/api/customer/certificationApi'
import CertificateModal from '@/components/customer/certification/CertificateModal.vue'

const route = useRoute()
const activeView = ref<'lesson' | 'quiz'>('lesson')
const currentQuizId = ref<number | null>(null)

const courseData = ref<any>(null)
const topics = ref<any[]>([])
const lessons = ref<any[]>([])
const currentLessonId = ref<number | null>(null)
const currentLessonData = ref<any>(null)

const isListLoading = ref(true)
const isLessonLoading = ref(false)
const isChatOpen = ref(false)
const activeThread = ref<any>(null)

const sidebarRef = ref()
const progress = ref(0)

const isCertificateModalOpen = ref(false)
const certificateData = ref(null)

const clientAuth = JSON.parse(localStorage.getItem('client_auth') || '{}')
const currentUser = ref(clientAuth?.user || null)

/* ============================
      FETCH LESSON LIST
=============================== */
const fetchLessonList = async () => {
  try {
    isListLoading.value = true

    const courseId = Number(route.params.courseId)
    if (!courseId) throw new Error('Không tìm thấy ID khóa học')

    const res = await lessonApi.getLessonListByCourseId(courseId)
    if (!res.success) throw new Error('Không thể tải danh sách bài học')

    courseData.value = res.data.course
    topics.value = res.data.topics || []

    lessons.value = topics.value.flatMap(topic =>
      (topic.lessons || []).map(lesson => ({
        ...lesson,
        status: lesson.is_completed ? 'completed' : 'available',
      }))
    )

    // Load last lesson or first lesson
    const savedLessonId = Number(localStorage.getItem(`lastLesson_${courseId}`))
    const firstLesson = topics.value[0]?.lessons?.[0]

    if (savedLessonId) {
      await fetchLessonDetail(savedLessonId)
    } else if (firstLesson) {
      currentLessonId.value = firstLesson.id
      await fetchLessonDetail(firstLesson.id)
    }

    // fetch realtime progress
    await fetchProgress()

  } catch (err: any) {
    notification.error({
      message: 'Lỗi tải khóa học',
      description: err.message,
    })
  } finally {
    isListLoading.value = false
  }
}

/* ============================
      FETCH PROGRESS
=============================== */
const fetchProgress = async () => {
  if (!courseData.value?.id) return

  const res = await lessonApi.getCourseProgress(courseData.value.id)
  progress.value = res.progress || 0
}

/* ============================
      FETCH LESSON DETAIL
=============================== */
const fetchLessonDetail = async (lessonId: number) => {
  try {
    isLessonLoading.value = true

    const res = await lessonApi.getLessonDetail(lessonId)
    if (!res.success) throw new Error('Không thể tải chi tiết bài học')

    const { lesson, course } = res.data
    currentLessonData.value = { ...lesson, course_title: course?.title || '' }
    currentLessonId.value = lessonId

    localStorage.setItem(`lastLesson_${courseData.value.id}`, lessonId.toString())

  } catch (err: any) {
    notification.error({
      message: 'Lỗi tải bài học',
      description: err.message,
    })
  } finally {
    isLessonLoading.value = false
  }
}

const openCertificate = async () => {
  const res = await certificationApi.getCertificateByCourse(courseData.value.id)

  if (!res.success || !res.certificate) {
    notification.error({
      message: "Chưa có chứng chỉ",
      description: "Bạn cần hoàn thành toàn bộ bài học để nhận chứng chỉ."
    })
    return
  }

  certificateData.value = res.certificate
  isCertificateModalOpen.value = true
}


const handleLessonCompleted = async () => {
  await fetchProgress()     
  await fetchLessonDetail(currentLessonId.value) 
}

const handleSelectLesson = (lessonId: number) => fetchLessonDetail(lessonId)

const handleOpenQuiz = (quizId: number) => {
  currentQuizId.value = quizId
  activeView.value = 'quiz'
}

const openQuiz = (quizId: number) => {
  currentQuizId.value = quizId
  activeView.value = 'quiz'
}

const handleSelectThread = (thread: any) => {
  activeThread.value = thread
}

const refreshSidebar = () => sidebarRef.value?.fetchThreads()

onMounted(() => fetchLessonList())

watch(
  () => route.params.courseId,
  () => fetchLessonList()
)
</script>

<style scoped>
.bg-info {
  background-color: #eaf5ff;
}
</style>
