<template>
  <div
    class="relative h-[calc(100vh-160px)] overflow-hidden rounded-3xl bg-gradient-to-br from-white via-[#f0fdfc] to-[#e6f9f9] p-6 md:p-10 shadow-2xl border border-teal-100/60 text-center">
    <div class="relative z-10 flex flex-col items-center justify-center h-full">

      <!-- Hero Icon (nhỏ hơn một chút) -->
      <div class="relative w-24 h-24 mx-auto mb-8 group">
        <div class="absolute inset-0 animate-ping rounded-3xl bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] opacity-20">
        </div>
        <div
          class="relative flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8] shadow-2xl transition-all duration-500 group-hover:scale-110 group-hover:shadow-3xl">
          <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
        </div>
      </div>

      <!-- Title (giảm 1 cấp) -->
      <div class="mb-10">
        <h2
          class="mb-3 text-4xl font-black tracking-tight md:text-5xl bg-gradient-to-r from-[#2ea5a8] to-[#49bbbd] bg-clip-text text-transparent">
          Ready to Test Yourself?
        </h2>
        <p class="text-lg font-medium text-gray-600">
          Challenge your knowledge with this quiz!
        </p>
      </div>

      <!-- Quiz Info Cards (giảm nhẹ) -->
      <div class="grid w-full max-w-3xl grid-cols-1 gap-6 md:grid-cols-2 mb-6">
        <div
          class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-[#e8f9f9] to-[#dbf5f5] p-6 shadow-md border border-[#49bbbd]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
          <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] shadow-lg transition-transform duration-300 group-hover:scale-110">
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="text-left">
            <p class="text-base font-bold text-gray-800">Time Limit</p>
            <p class="text-xl font-black text-[#2ea5a8]">
              {{ quiz.duration_minutes ? `${quiz.duration_minutes} min` : 'No limit' }}
            </p>
          </div>
        </div>

        <div
          class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-[#e8f9f9] to-[#dbf5f5] p-6 shadow-md border border-[#49bbbd]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
          <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] shadow-lg transition-transform duration-300 group-hover:scale-110">
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div class="text-left">
            <p class="text-base font-bold text-gray-800">Questions</p>
            <p class="text-xl font-black text-[#2ea5a8]">
              {{ quiz.total_questions || 0 }}
            </p>
          </div>
        </div>
      </div>

      <button @click="$emit('start')" :disabled="loading" class="group relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8]
         px-10 py-5 text-xl font-bold text-white shadow-xl
         transition-all duration-400 hover:shadow-2xl hover:-translate-y-2 flex items-center justify-center gap-3">
        <!-- Hover overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#2ea5a8] to-[#49bbbd] opacity-0 
           transition-opacity duration-300 group-hover:opacity-100"></div>

        <!-- Content -->
        <div class="relative z-10 flex items-center justify-center gap-3">
          <!-- ICON WRAPPER FIXED -->
          <div class="flex items-center justify-center w-6 h-6">
            <svg v-if="loading" class="animate-spin text-white w-6 h-6" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>

            <svg v-else class="text-white w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>

          <!-- TEXT -->
          <span class="whitespace-nowrap">{{ loading ? 'Loading...' : 'Start Quiz' }}</span>
        </div>
      </button>

      <div v-if="attempts.length" class="mt-12 w-full max-w-5xl h-[clac(100vh-500px)] overflow-y-auto">
        <div class="overflow-hidden rounded-3xl bg-white/70 backdrop-blur-md shadow-2xl border border-teal-100">
          <h3
            class="mb-6 pt-8 text-2xl font-black bg-gradient-to-r from-[#2ea5a8] to-[#49bbbd] bg-clip-text text-transparent">
            Your Previous Attempts
          </h3>
          <!-- Table giữ nguyên, chỉ giảm nhẹ padding nếu muốn -->
          <div class="overflow-x-auto px-6 pb-6">
            <table class="w-full text-left text-gray-700">
              <thead class="border-b-2 border-teal-200/50 text-xs font-bold uppercase tracking-wider text-gray-600">
                <tr>
                  <th class="pb-4 pl-6">Attempt</th>
                  <th class="pb-4">Score</th>
                  <th class="pb-4">Correct</th>
                  <th class="pb-4">Wrong</th>
                  <th class="pb-4">Submitted</th>
                  <th class="pb-4 text-center">Review</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200/50">
                <tr v-for="a in attempts" :key="a.id" class="transition-colors hover:bg-teal-50/50">
                  <td class="py-5 pl-6 font-bold text-gray-800">#{{ a.attempt_no }}</td>
                  <td class="py-5 font-black text-xl text-[#2ea5a8]">{{ a.score }}<span
                      class="text-base text-gray-500">/{{ a.max_score }}</span></td>
                  <td class="py-5 font-bold text-green-600">{{ a.correct_count }}</td>
                  <td class="py-5 font-bold text-red-600">{{ a.wrong_count }}</td>
                  <td class="py-5 text-gray-600">
                    <FormatDate :date="a.submitted_at" />
                  </td>
                  <td class="py-5 text-center">
                    <router-link :to="`/quiz/${quiz.id}/review/${a.id}`"
                      class="inline-flex items-center rounded-xl bg-gradient-to-r from-[#49bbbd] to-[#2ea5a8] px-5 py-2.5 font-bold text-white shadow-md transition-all hover:scale-105 hover:shadow-lg">
                      Review
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div v-else class="mt-10 rounded-2xl bg-white/40 backdrop-blur-sm p-6 border border-teal-100">
        <p class="text-base italic text-gray-600">
          You haven't taken this quiz yet. Be the first!
        </p>
      </div>

    </div>
  </div>
</template>
<script setup lang="ts">
import type { Quiz } from '@/types/Quiz'
import { ref, onMounted, watch } from 'vue'
import { quizApi } from '@/api/customer/quizApi'
import { notification } from 'ant-design-vue'
import FormatDate from '@/components/common/FormatDate.vue'

const props = defineProps<{
  quiz: Quiz
  loading: boolean
}>()

defineEmits<{
  start: []
}>()

const attempts = ref<any[]>([])

onMounted(() => {
  if (props.quiz?.id) fetchAttempts(props.quiz.id)
})

watch(
  () => props.quiz?.id,
  (newId) => {
    if (newId) fetchAttempts(newId)
  }
)

async function fetchAttempts(quizId: number) {
  try {
    const res = await quizApi.getQuizAttempts(quizId)
    if (res.success && res.data?.attempts) {
      attempts.value = res.data.attempts
    }
  } catch (err) {
    console.error('Failed to load attempt history:', err)
    notification.error({
      message: 'Failed to Load History',
      description: 'Could not retrieve previous attempts. Please try again later.',
    })
  }
}
</script>