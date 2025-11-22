<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- ===== Header ===== -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
      <div class="px-4 py-6 flex items-center justify-between">
        <div class="flex-1 min-w-0">
          <h1 class="text-3xl font-bold text-gray-800 truncate mb-2" :title="lesson?.title">
            {{ lesson?.title || 'Loading lesson...' }}
          </h1>

          <div class="flex items-center gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <span>Lesson</span>
            </div>
          </div>
        </div>

        <!-- Take Quiz Button (Desktop) -->
        <button v-if="lesson?.quiz"
          class="group relative flex items-center gap-3 rounded-2xl bg-gradient-to-r from-teal-500 to-blue-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl"
          @click="$emit('open-quiz', lesson.quiz.id)">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Take Quiz</span>
        </button>
      </div>
    </header>

    <!-- ===== Main Content ===== -->
    <main class="flex-1 overflow-y-auto">
      <div class="max-w-6xl mx-auto p-4 space-y-8">

        <!-- Loading State -->
        <div v-if="loading || !lesson" class="flex flex-col items-center justify-center py-32">
          <div class="h-16 w-16 animate-spin rounded-full border-4 border-teal-200 border-t-teal-500 mb-6"></div>
          <p class="text-lg font-medium text-gray-600">Loading lesson content...</p>
        </div>

        <!-- Actual Content -->
        <template v-else>

          <!-- Video Player -->
          <div class="overflow-hidden rounded-3xl bg-white shadow-2xl border border-gray-200/60">
            <div class="aspect-video bg-black relative">
              <div id="yt-player" class="absolute inset-0 w-full h-full"></div>
            </div>
          </div>

          <!-- Lesson Content Card -->
          <div class="rounded-3xl bg-white p-10 shadow-2xl border border-gray-200/60">
            <div class="mb-10 flex items-center gap-5">
              <div
                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-blue-500 shadow-lg">
                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-1">Lesson Content</h3>
                <p class="text-gray-600">Dive deep into this topic</p>
              </div>
            </div>

            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
              <div v-if="lesson.content" class="whitespace-pre-line" v-html="formattedContent"></div>

              <!-- Empty Content -->
              <div v-else class="py-16 text-center">
                <svg class="mx-auto mb-5 h-20 w-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xl font-medium text-gray-400">No content available yet</p>
                <p class="mt-2 text-gray-500">Content will be added soon</p>
              </div>
            </div>
          </div>

          <!-- Quiz Card (Mobile Only) -->
          <div v-if="lesson?.quiz"
            class="md:hidden rounded-3xl bg-gradient-to-r from-indigo-500 to-purple-600 p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                  <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="text-2xl font-bold mb-1">Knowledge Check Quiz</h4>
                  <p class="text-white/80 text-base">Test your understanding</p>
                </div>
              </div>

              <button
                class="rounded-2xl bg-white px-8 py-4 font-bold text-indigo-600 shadow-lg transition-all hover:scale-105"
                @click="$emit('open-quiz', lesson.quiz.id)">
                Start Quiz
              </button>
            </div>
          </div>

        </template>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, watch } from 'vue'
import { lessonApi } from '@/api/customer/lessonApi'

let player: any = null

const emit = defineEmits<{
  'open-quiz': [quizId: number]
  'lesson-completed': [lessonId: number]
}>()

const props = defineProps<{
  lesson: any | null
  lessons: any[]
  loading: boolean
}>()

/* ============================
   YouTube Player Setup
============================ */
onMounted(() => {
  loadYouTubePlayer()
})

watch(() => props.lesson?.video_id, () => {
  loadYouTubePlayer()
})

const loadYouTubePlayer = () => {
  if (!props.lesson?.video_id) return

  // Destroy previous player
  if (player?.destroy) {
    player.destroy()
  }

  if (!window.YT) {
    const tag = document.createElement('script')
    tag.src = 'https://www.youtube.com/iframe_api'
    document.body.appendChild(tag)

    window.onYouTubeIframeAPIReady = initPlayer
  } else {
    initPlayer()
  }
}

const initPlayer = () => {
  player = new window.YT.Player('yt-player', {
    height: '100%',
    width: '100%',
    videoId: props.lesson.video_id,
    playerVars: {
      rel: 0,
      modestbranding: 1,
      autoplay: 0,
      controls: 1,
      fs: 1,
    },
    events: {
      onStateChange: handleVideoState,
    },
  })
}

const handleVideoState = async (event: any) => {
  if (event.data === window.YT.PlayerState.ENDED) {
    await lessonApi.completeLesson(props.lesson.id)
    emit('lesson-completed', props.lesson.id)
  }
}

const formattedContent = computed(() => {
  if (!props.lesson?.content) return ''

  return props.lesson.content
    .replace(/\n/g, '<br>')
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.*?)\*/g, '<em>$1</em>')
})
</script>