<template>
  <div class="space-y-5">
    <div
      v-for="topic in topics"
      :key="topic.id"
      class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 shadow-lg transition-all duration-300 overflow-hidden group"
    >
      <!-- 🧩 TOPIC -->
      <LearningItem
        :title="topic.title"
        :expandable="true"
        :expanded="expandedTopics.includes(topic.id)"
        @click="toggleTopic(topic.id)"
      >
        <template #icon>
          <div
            class="w-10 h-10 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] 
                   rounded-xl flex items-center justify-center shadow-md"
          >
            <svg
              class="w-5 h-5 text-white"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
          </div>
        </template>

        <template #extra>
          <!-- Số lượng bài học -->
          <span
            class="text-xs font-medium px-2 py-1 rounded-full bg-[#e8f9f9] text-[#2ea5a8]"
          >
            {{ topic.lessons?.length || 0 }} bài học
          </span>

          <!-- Tick topic hoàn thành -->
          <svg
            v-if="isTopicCompleted(topic)"
            class="w-5 h-5 text-green-500 ml-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </template>
      </LearningItem>

      <!-- ▼▼ LESSON LIST ▼▼ -->
      <transition name="fade">
        <div v-if="expandedTopics.includes(topic.id)" class="space-y-2 p-3">

          <div v-for="lesson in topic.lessons" :key="lesson.id">
            <!-- 🧠 LESSON -->
            <LearningItem
              :title="lesson.title"
              :expandable="!!lesson.quiz"
              :expanded="expandedLessons.includes(lesson.id)"
              :is-active="lesson.id === currentLessonId"
              @click="toggleLesson(lesson.id)"
            >
              <template #icon>
                <svg
                  class="w-6 h-6 text-[#49bbbd] flex-shrink-0"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M5 20V4h2v7l2.5-1.5L12 11V4h5v7.08c.33-.05.66-.08 1-.08s.67.03 1 .08V4c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h7.26c-.42-.6-.75-1.28-.97-2zm13-7c-2.76 0-5 2.24-5 5s2.24 5 5 5s5-2.24 5-5s-2.24-5-5-5m-1.25 7.5v-5l4 2.5z"
                  />
                </svg>
              </template>

              <template #extra>
                <!-- Quiz badge -->
                <span
                  v-if="lesson.quiz"
                  class="ml-2 px-2 py-0.5 text-xs font-medium rounded bg-[#e8f9f9] text-[#2ea5a8]"
                >
                  Quiz
                </span>

                <!-- Tick bài học -->
                <svg
                  v-if="lesson.is_completed"
                  class="w-5 h-5 text-green-500 ml-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </template>
            </LearningItem>

            <!-- ▼ QUIZ CỦA BÀI -->
            <div
              v-if="lesson.quiz && expandedLessons.includes(lesson.id)"
              class="lesson-quiz cursor-pointer bg-[#e8f9f9] hover:bg-[#d4f4f4]"
              @click.stop="openLessonQuiz(lesson)"
            >
              <div class="flex items-center gap-2 text-[#2ea5a8] font-medium">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M4 6H2v14c..." />
                </svg>
                <span class="truncate max-w-[220px]">{{ lesson.quiz.title }}</span>
              </div>
              <span class="text-xs font-semibold text-[#2ea5a8]">Làm bài</span>
            </div>
          </div>

          <!-- 🧩 QUIZ CUỐI TOPIC -->
          <div
            v-if="topic.quiz"
            class="topic-quiz cursor-pointer bg-[#dbf5f5] hover:bg-[#c7eeee]"
            @click.stop="openTopicQuiz(topic)"
          >
            <div class="flex items-center gap-2 text-[#2ea5a8] font-semibold">
              <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4 6H2v14..." />
              </svg>
              <span class="truncate max-w-[220px]">{{ topic.quiz.title }}</span>
            </div>
            <span class="text-sm font-medium text-[#2ea5a8]">Làm bài</span>
          </div>

        </div>
      </transition>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import LearningItem from './LearningItem.vue'

const props = defineProps<{
  topics: any[]
  currentLessonId: number | null
}>()

const emit = defineEmits(['select-lesson', 'open-quiz'])

const STORAGE_KEY = 'learning_sidebar_state'
const expandedTopics = ref<number[]>([])
const expandedLessons = ref<number[]>([])

onMounted(() => {
  const saved = localStorage.getItem(STORAGE_KEY)
  if (saved) {
    try {
      const state = JSON.parse(saved)
      expandedTopics.value = state.expandedTopics || []
      expandedLessons.value = state.expandedLessons || []
    } catch {}
  }
})

watch(
  [expandedTopics, expandedLessons],
  ([topics, lessons]) => {
    localStorage.setItem(
      STORAGE_KEY,
      JSON.stringify({ expandedTopics: topics, expandedLessons: lessons })
    )
  },
  { deep: true }
)

/* ============================
   🔥 Logic Tick Complete
============================ */

// topic hoàn thành = tất cả lessons completed
const isTopicCompleted = (topic: any) => {
  if (!topic?.lessons?.length) return false
  return topic.lessons.every((l: any) => l.is_completed)
}

/* ============================
   🔥 Actions
============================ */
const toggleTopic = (topicId: number) => {
  expandedTopics.value = expandedTopics.value.includes(topicId)
    ? expandedTopics.value.filter(id => id !== topicId)
    : [...expandedTopics.value, topicId]
}

const toggleLesson = (lessonId: number) => {
  expandedLessons.value = expandedLessons.value.includes(lessonId)
    ? expandedLessons.value.filter(id => id !== lessonId)
    : [...expandedLessons.value, lessonId]

  emit('select-lesson', lessonId)
}

const openLessonQuiz = (lesson: any) => {
  emit('select-lesson', lesson.id)
  emit('open-quiz', lesson.quiz.id)
}

const openTopicQuiz = (topic: any) => {
  emit('open-quiz', topic.quiz.id)
}
</script>

<style scoped>
.lesson-quiz,
.topic-quiz {
  @apply flex items-center justify-between gap-2 h-[48px] rounded-xl px-4 transition;
  margin-left: 2.5rem;
}

.lesson-quiz:hover,
.topic-quiz:hover {
  @apply shadow-md;
}
</style>
