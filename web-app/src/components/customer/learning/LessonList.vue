<template>
  <div class="px-6 pb-10 relative">
    <!-- Header -->
    <div class="flex items-center gap-4 pt-6 pb-2">
      <button
        class="group w-12 h-12 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl flex items-center justify-center hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        @click="$router.back()"
      >
        <svg class="w-6 h-6 text-white transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold text-gray-800 truncate mb-1" :title="course?.title">
          {{ course?.title || 'Khóa học' }}
        </h2>
        <p class="text-sm text-gray-500 font-medium">Nội dung khóa học</p>
      </div>
    </div>

    <!-- Progress indicator -->
    <div class="mb-6 px-1">
      <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
        <span>Tiến độ học tập</span>
        <span class="font-semibold">{{ progressPercentage }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-teal-500 to-blue-500 rounded-full transition-all duration-700 ease-out"
          :style="{ width: `${progressPercentage}%` }"
        ></div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="space-y-4 mt-6">
      <div v-for="i in 6" :key="i" class="group">
        <div class="h-16 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-xl animate-pulse bg-[length:200%_100%] animate-shimmer"></div>
      </div>
    </div>

    <!-- Tree -->
    <div v-else class="mt-6">
      <LearningTree
        :topics="topics"
        :currentLessonId="currentLessonId"
        @select-lesson="handleSelectLesson"
        @open-quiz="handleOpenQuiz"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import LearningTree from './LearningTree.vue'

const props = defineProps<{
  course: { id: number; title: string } | null
  topics: any[]
  loading: boolean
  currentLessonId: number | null
}>()

const emit = defineEmits(['select-lesson', 'open-quiz'])

const handleSelectLesson = (lessonId: number) => {
  console.log('📘 Lesson selected:', lessonId)
  emit('select-lesson', lessonId)
}

const handleOpenQuiz = (quizId: number) => {
  console.log('🎯 Quiz opened:', quizId)
  emit('open-quiz', quizId)
}

const progressPercentage = computed(() => {
  if (!props.topics?.length) return 0
  let total = 0, completed = 0
  props.topics.forEach((topic: any) => {
    if (topic.lessons) {
      total += topic.lessons.length
      completed += topic.lessons.filter((l: any) => l.is_completed).length
    }
  })
  return total ? Math.round((completed / total) * 100) : 0
})
</script>
