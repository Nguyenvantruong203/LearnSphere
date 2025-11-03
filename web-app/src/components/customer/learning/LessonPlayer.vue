<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- ===== Header ===== -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
      <div class="px-4 py-6 flex items-center justify-between">
        <div class="flex-1 min-w-0">
          <h1
            class="text-3xl font-bold text-gray-800 truncate mb-2"
            :title="lesson?.title"
          >
            {{ lesson?.title || 'Đang tải bài học...' }}
          </h1>
          <div class="flex items-center gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <span>Bài học</span>
            </div>
          </div>
        </div>

        <!-- Quiz button (desktop) -->
        <button
          v-if="lesson?.quiz"
          class="group relative px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-white rounded-2xl text-base font-semibold transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-0.5 overflow-hidden flex items-center gap-3"
          @click="$emit('open-quiz', lesson.quiz.id)"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Làm bài Quiz</span>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
        </button>
      </div>
    </header>

    <!-- ===== Main content ===== -->
    <main class="flex-1 overflow-y-auto">
      <div class="max-w-6xl mx-auto p-3 space-y-6">
        <!-- Loading state -->
        <div v-if="loading || !lesson" class="flex flex-col items-center justify-center py-20">
          <div class="w-16 h-16 border-4 border-teal-200 border-t-teal-500 rounded-full animate-spin mb-6"></div>
          <p class="text-gray-600 text-lg font-medium">Đang tải dữ liệu bài học...</p>
        </div>

        <!-- Lesson content -->
        <template v-else>
          <!-- Video Player -->
          <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-200/60">
            <div class="aspect-video bg-gradient-to-br from-gray-900 to-black relative">
              <iframe
                v-if="lesson.video_provider === 'youtube' && lesson.video_id"
                :src="youtubeUrl"
                class="w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>

              <div
                v-else
                class="flex flex-col items-center justify-center h-full text-gray-400"
              >
                <svg class="w-20 h-20 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-medium">Chưa có video cho bài học này</p>
                <p class="text-sm text-gray-500 mt-1">Video sẽ được cập nhật sớm</p>
              </div>
            </div>
          </div>

          <!-- Content Card -->
          <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200/60">
            <!-- Header -->
            <div class="flex items-center gap-4 mb-8">
              <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-blue-500 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-1">Nội dung bài học</h3>
                <p class="text-gray-600">Tìm hiểu chi tiết về chủ đề này</p>
              </div>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
              <div
                v-if="lesson.content"
                class="text-gray-700 leading-relaxed whitespace-pre-line"
                v-html="formatContent(lesson.content)"
              >
              </div>
              <div v-else class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-400 text-lg font-medium">Chưa có nội dung mô tả</p>
                <p class="text-gray-500 text-sm mt-1">Nội dung sẽ được cập nhật sớm</p>
              </div>
            </div>
          </div>

          <!-- Quiz Card (Mobile) -->
          <div v-if="lesson.quiz" class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl p-8 text-white md:hidden">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="text-xl font-bold mb-1">Quiz kiểm tra</h4>
                  <p class="text-white/80">Kiểm tra hiểu biết của bạn</p>
                </div>
              </div>
              <button
                class="px-6 py-3 bg-white text-indigo-600 rounded-2xl font-semibold hover:bg-gray-50 transition-colors"
                @click="$emit('open-quiz', lesson.quiz.id)"
              >
                Bắt đầu
              </button>
            </div>
          </div>
        </template>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  lesson: any | null
  lessons: any[]
  loading: boolean
}>()

defineEmits(['open-quiz'])

const youtubeUrl = computed(() => {
  if (!props.lesson?.video_id) return ''
  return `https://www.youtube.com/embed/${props.lesson.video_id}?rel=0&modestbranding=1&autoplay=0`
})

// Format content để hiển thị đẹp hơn
const formatContent = (content: string) => {
  if (!content) return ''
  
  // Thay thế line breaks thành <br>
  return content
    .replace(/\n/g, '<br>')
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Bold text
    .replace(/\*(.*?)\*/g, '<em>$1</em>') // Italic text
}
</script>

<style scoped>
.aspect-video {
  aspect-ratio: 16 / 9;
}
</style>
