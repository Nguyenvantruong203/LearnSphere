<template>
  <div class="min-h-screen bg-gradient-to-br from-[#e8f9f9] via-[#dbf5f5] to-[#f3ffff] flex flex-col">
    <!-- Header -->
    <QuizHeader :title="quiz?.title || 'Quiz'" @exit="$emit('exit')" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="max-w-5xl mx-auto p-6 md:p-8">
        <!-- Intro Screen -->
        <QuizIntro
          v-if="!started"
          :quiz="quiz || { duration_minutes: 0, total_questions: 0 }"
          :loading="loading"
          @start="startQuiz"
        />

        <!-- Quiz in Progress -->
        <div v-else class="space-y-10">
          <!-- Timer & Progress Card -->
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-teal-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
              <!-- Timer -->
              <div class="flex items-center gap-5">
                <div class="relative">
                  <div class="absolute inset-0 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-3xl animate-pulse opacity-20"></div>
                  <div class="relative w-14 h-14 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-3xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Time Remaining</p>
                  <p class="text-3xl font-bold text-[#2ea5a8]">{{ formattedTime }}</p>
                </div>
              </div>

              <!-- Progress -->
              <div class="flex items-center gap-5 text-right">
                <div>
                  <p class="text-sm font-medium text-gray-500">Progress</p>
                  <p class="text-3xl font-bold text-[#2ea5a8]">{{ answeredCount }} / {{ questions.length }}</p>
                </div>
                <div class="relative">
                  <div class="absolute inset-0 bg-gradient-to-br from-[#49bbbd] to-[#68d6d8] rounded-3xl animate-pulse opacity-20"></div>
                  <div class="relative w-14 h-14 bg-gradient-to-br from-[#49bbbd] to-[#68d6d8] rounded-3xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">{{ Math.round((answeredCount / questions.length) * 100) }}%</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Progress</span>
                <span>{{ answeredCount }} / {{ questions.length }} answered</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                <div
                  class="h-full bg-gradient-to-r from-[#49bbbd] to-[#68d6d8] rounded-full transition-all duration-700"
                  :style="{ width: `${(answeredCount / questions.length) * 100}%` }"
                ></div>
              </div>
            </div>
          </div>

          <!-- Questions -->
          <div class="space-y-10">
            <div
              v-for="(q, index) in questions"
              :key="q.id"
              class="group bg-white/90 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-teal-100 hover:shadow-2xl hover:border-[#49bbbd]/40 transition-all duration-400"
            >
              <div class="flex items-start gap-6 mb-7">
                <div class="relative flex-shrink-0">
                  <div class="relative w-12 h-12 bg-gradient-to-br from-[#49bbbd] to-[#68d6d8] rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">{{ index + 1 }}</span>
                  </div>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-800 leading-relaxed mb-3">{{ q.text }}</h3>
                  <span class="inline-block px-4 py-1.5 bg-[#e8f9f9] text-[#2ea5a8] text-sm font-semibold rounded-full">
                    {{ q.type === 'multiple_choice' ? 'Multiple Choice' : 'Single Choice' }}
                  </span>
                </div>
              </div>

              <!-- Options -->
              <div class="space-y-4 ml-18">
                <div
                  v-for="(opt, i) in q.options"
                  :key="i"
                  @click="selectAnswer(q.id, i)"
                  class="group/option relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300 hover:shadow-md"
                  :class="{
                    'border-[#49bbbd] bg-gradient-to-r from-[#e8f9f9] to-[#dbf5f5] shadow-md': isAnswerSelected(q.id, i),
                    'border-gray-200 hover:border-[#68d6d8] hover:bg-[#f0fcfc]': !isAnswerSelected(q.id, i)
                  }"
                >
                  <div class="flex items-center gap-4">
                    <div class="relative">
                      <div
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                        :class="{
                          'border-[#49bbbd] bg-[#49bbbd] text-white shadow-lg': isAnswerSelected(q.id, i),
                          'border-gray-300 group-hover/option:border-[#68d6d8]': !isAnswerSelected(q.id, i)
                        }"
                      >
                        <svg v-if="isAnswerSelected(q.id, i)" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                    <div class="flex items-center gap-3 flex-1">
                      <span class="font-bold text-gray-500 text-lg min-w-[36px]">{{ String.fromCharCode(65 + i) }}.</span>
                      <span class="text-gray-800 font-medium text-lg">{{ opt }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Section -->
          <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-10 shadow-2xl border border-teal-100 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-3xl flex items-center justify-center shadow-xl">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>

            <h3 class="text-3xl font-bold text-[#2ea5a8] mb-3">Ready to Submit?</h3>
            <p class="text-lg text-gray-600 mb-2">Your current progress</p>
            <p class="text-2xl font-bold text-[#49bbbd]">
              {{ answeredCount }} / {{ questions.length }}
              <span class="text-lg text-gray-500"> ({{ Math.round((answeredCount / questions.length) * 100) }}%)</span>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
              <button
                @click="$emit('exit')"
                class="px-8 py-3.5 text-gray-600 border-2 border-gray-300 rounded-2xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all"
              >
                Exit Quiz
              </button>
              <button
                @click="submitQuiz"
                :disabled="submitting"
                class="group relative px-10 py-4 bg-gradient-to-r from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 overflow-hidden"
              >
                <div class="absolute inset-0 bg-gradient-to-r from-[#2ea5a8] to-[#49bbbd] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10 flex items-center gap-3">
                  <svg v-if="submitting" class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                  </svg>
                  <span>{{ submitting ? 'Submitting...' : 'Submit Quiz' }}</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted, nextTick, h } from 'vue'
import { quizApi } from '@/api/customer/quizApi'
import type { Quiz } from '@/types/Quiz'
import type { Question } from '@/types/Question'
import { notification, Modal } from 'ant-design-vue'
import QuizHeader from '@/components/customer/quiz/QuizHeader.vue'
import QuizIntro from '@/components/customer/quiz/QuizIntro.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps<{ quizId: number }>()
const emit = defineEmits(['exit'])

const quiz = ref<Quiz | null>(null)
const questions = ref<Question[]>([])
const loading = ref(false)
const submitting = ref(false)
const started = ref(false)
const timeLeft = ref(0)
const timer = ref<number | null>(null)
const answers = ref<Record<number, number | number[] | null>>({})
const attemptId = ref<number | null>(null)

const answeredCount = computed(() =>
  Object.values(answers.value).filter(ans => {
    if (Array.isArray(ans)) return ans.length > 0
    return ans !== null
  }).length
)

const formattedTime = computed(() => {
  const m = Math.floor(timeLeft.value / 60)
  const s = timeLeft.value % 60
  return `${m}:${s.toString().padStart(2, '0')}`
})

onMounted(async () => {
  loading.value = true
  try {
    const res = await quizApi.getQuizDetail(props.quizId)
    if (res.success && res.data) {
      quiz.value = res.data.quiz
      questions.value = res.data.questions || []
    }
  } finally {
    loading.value = false
  }
})

async function startQuiz() {
  loading.value = true
  try {
    const res = await quizApi.startQuizAttempt(props.quizId)
    if (res.success && res.data) {
      started.value = true
      quiz.value = res.data.quiz
      questions.value = res.data.questions || []
      attemptId.value = res.data.attempt.id

      notification.success({
        message: 'Quiz Started',
        description: `You have ${quiz.value?.duration_minutes || 'unlimited'} minutes to complete ${questions.value.length} questions.`,
      })

      timeLeft.value = (quiz.value?.duration_minutes || 10) * 60

      answers.value = Object.fromEntries(questions.value.map(q => [q.id, null]))

      timer.value = window.setInterval(() => {
        if (timeLeft.value > 0) {
          timeLeft.value--
        } else {
          clearInterval(timer.value!)
          notification.warning({ message: 'Time’s Up!', description: 'Your quiz will be auto-submitted.' })
          submitQuiz()
        }
      }, 1000)
    }
  } finally {
    loading.value = false
  }
}

function isAnswerSelected(questionId: number, optionIndex: number): boolean {
  const answer = answers.value[questionId]
  if (Array.isArray(answer)) return answer.includes(optionIndex)
  return answer === optionIndex
}

function selectAnswer(questionId: number, optionIndex: number) {
  const question = questions.value.find(q => q.id === questionId)
  if (!question) return

  if (question.type === 'multiple_choice') {
    const current = Array.isArray(answers.value[questionId]) ? answers.value[questionId] as number[] : []
    answers.value[questionId] = current.includes(optionIndex)
      ? current.filter(i => i !== optionIndex)
      : [...current, optionIndex]
  } else {
    answers.value[questionId] = optionIndex
  }
}

async function submitQuiz() {
  if (!attemptId.value) return

  submitting.value = true
  try {
    const formattedAnswers = Object.entries(answers.value).map(([qid, ans]) => ({
      question_id: +qid,
      selected_options: Array.isArray(ans) ? ans.map(String) : (ans !== null ? [String(ans)] : [])
    }))

    const res = await quizApi.submitQuizAttempt(props.quizId, { attempt_id: attemptId.value, answers: formattedAnswers })

    if (res.success && res.data) {
      Modal.success({
        title: 'Quiz Completed!',
        centered: true,
        width: 520,
        okText: 'View Results',
        content: h('div', { class: 'text-center space-y-4 py-4' }, [
          h('p', { class: 'text-2xl font-bold text-[#2ea5a8]' }, `Score: ${res.data.score}/${res.data.max_score}`),
          h('p', `Correct: ${res.data.correct_count} | Wrong: ${res.data.wrong_count}`),
          h('p', { class: 'text-sm text-gray-500' }, `Submitted at: ${new Date(res.data.submitted_at).toLocaleString()}`)
        ]),
        onOk: () => router.push(`/quiz/${props.quizId}/review/${res.data.attempt_id}`),
        onCancel: () => emit('exit')
      })

      started.value = false
      if (timer.value) clearInterval(timer.value)
    }
  } catch (err) {
    notification.error({ message: 'Submission Failed', description: 'Please try again.' })
  } finally {
    submitting.value = false
  }
}

onUnmounted(() => {
  if (timer.value) clearInterval(timer.value)
})
</script>