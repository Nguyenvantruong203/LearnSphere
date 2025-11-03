<template>
  <div class="space-y-5">
    <div
      v-for="(topic, tIndex) in topics"
      :key="topic.id"
      class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 shadow-lg transition-all duration-300 overflow-hidden group"
    >
      <!-- 🧩 Topic -->
      <LearningItem
        :title="topic.title"
        :expandable="true"
        :expanded="expandedTopics.includes(topic.id)"
        @click="toggleTopic(topic.id)"
        class="topic-header"
      >
        <template #icon>
          <div
            class="w-10 h-10 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-xl flex items-center justify-center flex-shrink-0 shadow-md transition-transform"
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
          <div class="flex items-center gap-2">
            <span
              class="text-xs font-medium px-2 py-1 rounded-full bg-[#e8f9f9] text-[#2ea5a8]"
            >
              {{ topic.lessons?.length || 0 }} bài học
            </span>
          </div>
        </template>
      </LearningItem>

      <transition name="fade">
        <div v-if="expandedTopics.includes(topic.id)" class="space-y-2 p-3">
          <!-- 🧠 Lessons -->
          <div v-for="(lesson, lIndex) in topic.lessons" :key="lesson.id">
            <LearningItem
              :title="lesson.title"
              :expandable="!!lesson.quiz"
              :expanded="expandedLessons.includes(lesson.id)"
              :is-active="lesson.id === currentLessonId"
              @click="toggleLesson(topic.id, lesson.id)"
            >
              <template #icon>
                <svg
                  class="w-6 h-6 text-[#49bbbd] flex-shrink-0"
                  xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M5 20V4h2v7l2.5-1.5L12 11V4h5v7.08c.33-.05.66-.08 1-.08s.67.03 1 .08V4c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h7.26c-.42-.6-.75-1.28-.97-2zm13-7c-2.76 0-5 2.24-5 5s2.24 5 5 5s5-2.24 5-5s-2.24-5-5-5m-1.25 7.5v-5l4 2.5z"
                  />
                </svg>
              </template>

              <template #extra>
                <span
                  v-if="lesson.quiz"
                  class="ml-2 px-2 py-0.5 text-xs font-medium rounded bg-[#e8f9f9] text-[#2ea5a8]"
                >
                  Quiz
                </span>
              </template>
            </LearningItem>

            <!-- ✅ Quiz của bài học -->
            <div
              v-if="lesson.quiz && expandedLessons.includes(lesson.id)"
              data-no-bubble
              class="lesson-quiz cursor-pointer bg-[#e8f9f9] hover:bg-[#d4f4f4]"
              @click.stop="handleOpenLessonQuiz(lesson)"
            >
              <div
                class="flex items-center gap-2 text-[#2ea5a8] font-medium min-w-0 flex-1"
              >
                <svg
                  class="w-5 h-5 flex-shrink-0"
                  xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2m0 14H8V4h12zm-6.49-5.84c.41-.73 1.18-1.16 1.63-1.8c.48-.68.21-1.94-1.14-1.94c-.88 0-1.32.67-1.5 1.23l-1.37-.57C11.51 5.96 12.52 5 13.99 5c1.23 0 2.08.56 2.51 1.26c.37.6.58 1.73.01 2.57c-.63.93-1.23 1.21-1.56 1.81c-.13.24-.18.4-.18 1.18h-1.52c.01-.41-.06-1.08.26-1.66m-.56 3.79c0-.59.47-1.04 1.05-1.04c.59 0 1.04.45 1.04 1.04c0 .58-.44 1.05-1.04 1.05c-.58 0-1.05-.47-1.05-1.05"
                  />
                </svg>
                <span
                  class="truncate block max-w-[220px]"
                  :title="lesson.quiz.title"
                >
                  {{ lesson.quiz.title || 'Quiz của bài học' }}
                </span>
              </div>
              <span class="text-xs font-semibold text-[#2ea5a8] flex-shrink-0"
                >Làm bài</span
              >
            </div>
          </div>

          <!-- 🧩 Quiz cuối topic -->
          <div
            v-if="topic.quiz"
            class="topic-quiz cursor-pointer bg-[#dbf5f5] hover:bg-[#c7eeee]"
            data-no-bubble
            @click.stop.prevent="handleOpenTopicQuiz(topic)"
          >
            <div
              class="flex items-center gap-2 text-[#2ea5a8] font-semibold min-w-0 flex-1"
            >
              <svg
                class="w-5 h-5 flex-shrink-0"
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
              >
                <path
                  fill="currentColor"
                  d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2m0 14H8V4h12zm-6.49-5.84c.41-.73 1.18-1.16 1.63-1.8c.48-.68.21-1.94-1.14-1.94c-.88 0-1.32.67-1.5 1.23l-1.37-.57C11.51 5.96 12.52 5 13.99 5c1.23 0 2.08.56 2.51 1.26c.37.6.58 1.73.01 2.57c-.63.93-1.23 1.21-1.56 1.81c-.13.24-.18.4-.18 1.18h-1.52c.01-.41-.06-1.08.26-1.66m-.56 3.79c0-.59.47-1.04 1.05-1.04c.59 0 1.04.45 1.04 1.04c0 .58-.44 1.05-1.04 1.05c-.58 0-1.05-.47-1.05-1.05"
                />
              </svg>
              <span
                class="truncate block max-w-[220px]"
                :title="topic.quiz.title"
              >
                {{ topic.quiz.title || 'Quiz cuối chương' }}
              </span>
            </div>
            <span class="text-sm font-medium text-[#2ea5a8] flex-shrink-0"
              >Làm bài</span
            >
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
    } catch (e) {
      console.error('Failed to parse sidebar state:', e)
    }
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

const toggleTopic = (topicId: number) => {
  expandedTopics.value = expandedTopics.value.includes(topicId)
    ? expandedTopics.value.filter((id) => id !== topicId)
    : [topicId]
}

const toggleLesson = (topicId: number, lessonId: number) => {
  if (expandedLessons.value.includes(lessonId)) {
    expandedLessons.value = expandedLessons.value.filter(
      (id) => id !== lessonId
    )
  } else {
    expandedLessons.value.push(lessonId)
  }
  emit('select-lesson', lessonId)
}

const handleOpenLessonQuiz = (lesson: any) => {
  emit('select-lesson', lesson.id)
  emit('open-quiz', lesson.quiz.id)
}

const handleOpenTopicQuiz = (topic: any) => {
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
