<template>
  <div class="px-6 pb-10">
    <!-- Header -->
    <div class="flex items-center gap-4 pt-6 pb-2">
      <button
        class="group w-12 h-12 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl flex items-center justify-center hover:-translate-x-0.5 hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg"
        @click="$router.back()">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold text-gray-800 truncate mb-1" :title="course?.title">
          {{ course?.title || 'Khóa học' }}
        </h2>
        <p class="text-sm text-gray-500 font-medium">Nội dung khóa học</p>
      </div>

      <button
        class="flex items-center gap-2 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium px-4 py-2 rounded-xl shadow-md hover:-translate-y-0.5 transition-all duration-300"
        @click="handleOpenChat">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h8m-8 4h5m9-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Chat</span>
      </button>
    </div>

    <!-- Progress indicator -->
    <div class="mb-6 px-1">
      <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
        <span>Tiến độ học tập</span>
        <span class="font-semibold">{{ props.progress || 0 }}%</span>
      </div>

      <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
        <div class="h-full bg-gradient-to-r from-teal-500 to-blue-500 rounded-full transition-all duration-700"
          :style="{ width: `${props.progress || 0}%` }"></div>
      </div>

      <!-- ⭐ Nút nhận chứng chỉ -->
      <button v-if="props.progress >= 100"
        class="mt-4 w-full bg-gradient-to-r from-amber-400 to-yellow-500 text-white font-semibold py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all"
        @click="emit('open-certificate')">
        Nhận chứng chỉ
      </button>
    </div>


    <!-- Loading -->
    <div v-if="loading" class="space-y-4 mt-6">
      <div v-for="i in 6" :key="i" class="group">
        <div class="h-16 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-xl animate-pulse"></div>
      </div>
    </div>

    <!-- Tree -->
    <div v-else class="mt-6">
      <LearningTree :topics="topics" :currentLessonId="currentLessonId" @select-lesson="handleSelectLesson"
        @open-quiz="handleOpenQuiz" />
    </div>
  </div>
</template>

<script setup lang="ts">
import LearningTree from './LearningTree.vue'

const props = defineProps<{
  course: { id: number; title: string } | null
  topics: any[]
  loading: boolean
  currentLessonId: number | null
  progress: number
}>()

const emit = defineEmits(['select-lesson', 'open-quiz', 'open-chat', 'open-certificate'])

const handleSelectLesson = (lessonId: number) => {
  emit('select-lesson', lessonId)
}

const handleOpenQuiz = (quizId: number) => {
  emit('open-quiz', quizId)
}

const handleOpenChat = () => {
  emit('open-chat')
}
</script>
