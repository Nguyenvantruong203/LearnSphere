<template>
  <div class="px-6 pb-10">
    <!-- Header -->
    <div class="flex items-center gap-4 pt-6 pb-2">
      <button
        class="group flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 shadow-lg transition-all duration-300 hover:-translate-x-1 hover:from-teal-600 hover:to-teal-700"
        @click="$router.back()"
      >
        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <div class="flex-1 min-w-0">
        <h2 class="truncate text-2xl font-bold text-gray-800 mb-1" :title="course?.title">
          {{ course?.title || 'Course' }}
        </h2>
        <p class="text-sm font-medium text-gray-500">Course Content</p>
      </div>

      <button
        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-blue-500 px-5 py-3 font-medium text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
        @click="handleOpenChat"
      >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h8m-8 4h5m9-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Chat</span>
      </button>
    </div>

    <!-- Progress Indicator -->
    <div class="mb-8 px-1">
      <div class="mb-3 flex items-center justify-between text-sm text-gray-600">
        <span>Learning Progress</span>
        <span class="font-semibold">{{ props.progress || 0 }}%</span>
      </div>

      <div class="h-3 overflow-hidden rounded-full bg-gray-200">
        <div
          class="h-full rounded-full bg-gradient-to-r from-teal-500 to-blue-500 transition-all duration-700 ease-out"
          :style="{ width: `${props.progress || 0}%` }"
        />
      </div>

      <!-- Claim Certificate Button -->
      <button
        v-if="props.progress >= 100"
        class="mt-5 w-full rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 py-4 font-bold text-white shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5"
        @click="emit('open-certificate')"
      >
        Claim Certificate
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="mt-8 space-y-5">
      <div v-for="i in 6" :key="i" class="h-16 rounded-xl bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 animate-pulse" />
    </div>

    <!-- Learning Tree -->
    <div v-else class="mt-8">
      <LearningTree
        :topics="topics"
        :current-lesson-id="currentLessonId"
        @select-lesson="handleSelectLesson"
        @open-quiz="handleOpenQuiz"
        @open-flashcards="handleOpenFlashcards"
      />
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

const emit = defineEmits<{
  'select-lesson': [lessonId: number]
  'open-quiz': [quizId: number]
  'open-chat': []
  'open-certificate': []
  'open-flashcards': [setId: number]
}>()

const handleOpenFlashcards = (setId: number) => {
  emit('open-flashcards', setId)
}

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