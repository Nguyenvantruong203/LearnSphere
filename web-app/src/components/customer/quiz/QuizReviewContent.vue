<template>
  <div class="space-y-8">
    <div v-if="attempt"
      class="relative overflow-hidden rounded-3xl bg-white/90 backdrop-blur-sm shadow-2xl border border-teal-100 p-10 text-center">
      <!-- Decorative blobs -->
      <div
        class="absolute top-0 right-0 w-72 h-72 bg-gradient-to-bl from-[#49bbbd]/15 to-[#68d6d8]/15 rounded-full -translate-y-36 translate-x-36">
      </div>
      <div
        class="absolute bottom-0 left-0 w-56 h-56 bg-gradient-to-tr from-[#a0e6e7]/15 to-[#49bbbd]/15 rounded-full translate-y-28 -translate-x-28">
      </div>

      <div class="relative z-10">
        <!-- Title + Icon -->
        <div class="mb-8 flex flex-col items-center">
          <div class="flex items-center gap-4 mb-4">
            <div
              class="w-14 h-14 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-2xl flex items-center justify-center shadow-xl">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h2
              class="text-3xl md:text-4xl font-black bg-gradient-to-r from-gray-800 via-[#2ea5a8] to-[#49bbbd] bg-clip-text text-transparent">
              Quiz Results
            </h2>
          </div>
          <p class="text-lg text-gray-600 font-medium">Here's how you performed</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
          <StatCard title="Score" color="teal" :value="`${attempt.score} / ${attempt.max_score}`" icon="trophy" />
          <StatCard title="Correct" color="green" :value="attempt.correct_count" icon="check-circle" />
          <StatCard title="Wrong" color="red" :value="attempt.wrong_count" icon="x-circle" />
          <StatCard title="Submitted" color="indigo" :value="formatDate(attempt.submitted_at)" icon="calendar" />
        </div>
      </div>
    </div>

    <!-- Detailed Answers -->
    <div v-if="questions.length" class="space-y-10">
      <div class="text-center mb-10">
        <h3
          class="text-3xl font-black bg-gradient-to-r from-gray-800 via-[#2ea5a8] to-[#49bbbd] bg-clip-text text-transparent mb-3">
          Answer Review
        </h3>
        <p class="text-lg text-gray-600">See your answers and the correct ones</p>
      </div>

      <!-- Each Question -->
      <div v-for="(q, index) in questions" :key="q.id"
        class="rounded-3xl p-8 shadow-xl border-2 transition-all duration-300 relative overflow-hidden" :class="q.is_correct
          ? 'bg-gradient-to-br from-[#f0fdfd] to-[#d1f7f7] border-[#49bbbd]/60'
          : 'bg-gradient-to-br from-[#fef5f5] to-[#ffe5e5] border-red-300/70'
          ">
        <div class="relative z-10">
          <!-- Question Header -->
          <div class="flex items-start gap-6 mb-6">
            <div
              class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg text-white font-bold text-xl flex-shrink-0"
              :class="q.is_correct ? 'bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8]' : 'bg-red-500'">
              <span v-if="q.is_correct">Correct</span>
              <span v-else>Wrong</span>
            </div>

            <div class="flex-1">
              <div class="flex items-center gap-4 mb-3">
                <span class="text-2xl font-black text-gray-700">{{ index + 1 }}.</span>
                <span class="px-4 py-1.5 rounded-full text-sm font-bold"
                  :class="q.is_correct ? 'bg-[#d1f7f7] text-[#2ea5a8]' : 'bg-red-100 text-red-700'">
                  {{ q.is_correct ? 'Correct' : 'Incorrect' }}
                </span>
              </div>

              <h3 class="text-xl md:text-2xl font-bold text-gray-800 leading-relaxed mb-3">
                {{ q.text }}
              </h3>

              <span class="inline-block px-4 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                {{ q.type === 'multiple_choice' ? 'Multiple Choice' : 'Single Choice' }}
              </span>
            </div>
          </div>

          <!-- Correct/Incorrect Badge -->
          <div class="mb-6">
            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm" :class="q.is_correct
              ? 'bg-[#d1f7f7] text-[#2ea5a8] border border-[#49bbbd]/50'
              : 'bg-red-100 text-red-700 border border-red-300'">
              <span v-if="q.is_correct">Great job! You got this one right</span>
              <span v-else">You got this one wrong</span>
            </span>
          </div>

          <!-- Options -->
          <div class="space-y-4">
            <div v-for="(optText, letter) in q.options" :key="letter"
              class="p-5 rounded-2xl border-2 flex items-center gap-5 transition-all" :class="{
                'border-[#49bbbd] bg-[#e8f9f9] shadow-md': q.correct_options.includes(letter),
                'border-red-400 bg-red-50 shadow-sm': isSelected(q, letter) && !q.correct_options.includes(letter),
                'border-gray-200 bg-gray-50': !isSelected(q, letter) && !q.correct_options.includes(letter),
              }">
              <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-sm" :class="{
                'bg-[#49bbbd] text-white': q.correct_options.includes(letter),
                'bg-red-500 text-white': isSelected(q, letter) && !q.correct_options.includes(letter),
                'bg-gray-300 text-gray-600': !isSelected(q, letter) && !q.correct_options.includes(letter),
              }">
                {{ letter.toUpperCase() }}
              </div>

              <p class="flex-1 font-medium text-gray-800 text-lg">{{ optText }}</p>

              <!-- Indicators -->
              <div v-if="q.correct_options.includes(letter)" class="text-green-600 font-bold">Correct Answer</div>
              <div v-else-if="isSelected(q, letter)" class="text-red-600 font-bold">Your Answer</div>
            </div>
          </div>

          <!-- Show Correct Answer if Wrong -->
          <div v-if="!q.is_correct" class="mt-6 p-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <p class="font-bold text-red-800">
              Correct Answer:
              <span class="ml-2 font-black text-gray-800">
                {{q.correct_options.map(l => `${l.toUpperCase()}. ${q.options[l]}`).join(', ')}}
              </span>
            </p>
            <p class="text-sm text-gray-700 mt-2">
              You selected:
              <span class="font-semibold text-gray-800">
                {{q.selected_options?.map(l => `${l.toUpperCase()}. ${q.options[l]}`).join(', ') || 'None'}}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-16">
      <button @click="$router.back()"
        class="group inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Back to Course</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { quizApi } from '@/api/customer/quizApi'
import { notification } from 'ant-design-vue'
import { useRoute } from 'vue-router'
import StatCard from '@/components/customer/quiz/StatCard.vue'

const route = useRoute()
const quizId = Number(route.params.id)
const attemptId = Number(route.params.attemptId)

const attempt = ref<any>(null)
const questions = ref<any[]>([])
const loading = ref(false)

onMounted(fetchReview)

async function fetchReview() {
  loading.value = true
  try {
    const res = await quizApi.getQuizReview(quizId, attemptId)
    if (res.success && res.data) {
      attempt.value = res.data.attempt
      questions.value = res.data.questions || []
    } else {
      notification.error({
        message: 'Failed to load review',
        description: res.message || 'Please try again later.',
      })
    }
  } catch (err) {
    console.error('Failed to load quiz review:', err)
    notification.error({
      message: 'Error loading review',
      description: 'An unexpected error occurred.',
    })
  } finally {
    loading.value = false
  }
}

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function isSelected(q: any, letter: string) {
  return q.selected_options?.includes(letter)
}
</script>