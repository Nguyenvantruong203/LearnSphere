<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex flex-col">
    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-sm border-b border-gray-200/50 shadow-sm sticky top-0 z-40">
      <div class="px-8 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">{{ quiz?.title || 'Quiz' }}</h1>
              <p class="text-gray-600">Kiểm tra kiến thức của bạn</p>
            </div>
          </div>
          
          <button
            class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors"
            @click="$emit('exit')"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="max-w-4xl mx-auto p-8">
        <!-- ⏳ Khi chưa bắt đầu -->
        <div v-if="!started" class="bg-white rounded-3xl p-10 shadow-xl border border-gray-200/60 text-center">
          <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>
          
          <h2 class="text-3xl font-bold text-gray-800 mb-4">Sẵn sàng làm bài?</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-left">
            <div class="flex items-center gap-3 p-4 bg-indigo-50 rounded-2xl">
              <div class="w-8 h-8 bg-indigo-500 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="font-semibold text-gray-800">Thời gian</p>
                <p class="text-gray-600">{{ quiz?.duration_minutes || 'Không giới hạn' }} phút</p>
              </div>
            </div>
            
            <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-2xl">
              <div class="w-8 h-8 bg-purple-500 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div>
                <p class="font-semibold text-gray-800">Số câu hỏi</p>
                <p class="text-gray-600">{{ questions.length || quiz?.total_questions || 0 }} câu</p>
              </div>
            </div>
          </div>

          <div class="flex gap-4 justify-center">
            <button
              class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-semibold text-lg hover:shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-3"
              @click="startQuiz" 
              :disabled="loading"
            >
              <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ loading ? 'Đang tải...' : 'Bắt đầu làm bài' }}
            </button>
          </div>
        </div>

        <!-- 🧠 Khi đang làm bài -->
        <div v-else class="space-y-8">
          <!-- Timer & Progress -->
          <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-200/60">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Thời gian còn lại</p>
                  <p class="text-2xl font-bold text-gray-800">{{ formattedTime }}</p>
                </div>
              </div>
              
              <div class="text-right">
                <p class="text-sm text-gray-600">Tiến độ</p>
                <p class="text-lg font-semibold text-indigo-600">{{ answeredCount }}/{{ questions.length }}</p>
              </div>
            </div>
          </div>

          <!-- Questions -->
          <div v-if="questions.length" class="space-y-6">
            <div
              v-for="(q, index) in questions"
              :key="q.id"
              class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200/60"
            >
              <div class="flex items-start gap-4 mb-6">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                  <span class="text-white font-bold text-sm">{{ index + 1 }}</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 leading-relaxed">
                  {{ q.text }}
                </h3>
              </div>

              <div class="space-y-3 ml-12">
                <div
                  v-for="(opt, i) in q.options"
                  :key="i"
                  @click="selectAnswer(q.id, i)"
                  class="group p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300"
                  :class="{
                    'border-indigo-500 bg-indigo-50 shadow-md': answers[q.id] === i,
                    'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50': answers[q.id] !== i
                  }"
                >
                  <div class="flex items-center gap-4">
                    <div 
                      class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                      :class="{
                        'border-indigo-500 bg-indigo-500': answers[q.id] === i,
                        'border-gray-300 group-hover:border-indigo-400': answers[q.id] !== i
                      }"
                    >
                      <svg v-if="answers[q.id] === i" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-500 w-6">{{ String.fromCharCode(65 + i) }}.</span>
                    <span class="text-gray-800 font-medium">{{ opt }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Section -->
          <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200/60 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Hoàn thành bài thi</h3>
            <p class="text-gray-600 mb-8">Bạn đã trả lời {{ answeredCount }}/{{ questions.length }} câu hỏi</p>
            
            <div class="flex gap-4 justify-center">
              <button
                class="px-6 py-3 text-gray-600 border border-gray-300 rounded-2xl font-semibold hover:bg-gray-50 transition-colors"
                @click="$emit('exit')"
              >
                Thoát
              </button>
              <button
                class="px-8 py-3 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-2xl font-semibold hover:shadow-lg hover:shadow-green-500/25 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-3"
                @click="submitQuiz" 
                :disabled="submitting"
              >
                <svg v-if="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ submitting ? 'Đang nộp bài...' : 'Nộp bài' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted } from 'vue'
import { quizApi } from '@/api/customer/quizApi'
import type { Quiz } from '@/types/Quiz'
import type { Question } from '@/types/Question'

const props = defineProps<{ quizId: number }>()
const emit = defineEmits(['exit'])

// Types
interface QuizData {
  id: number
  title: string
  duration_minutes: number
  total_questions: number
  questions: Question[]
}

const quiz = ref<QuizData | null>(null)
const questions = ref<Question[]>([])
const loading = ref(false)
const submitting = ref(false)
const started = ref(false)
const timeLeft = ref(0)
const timer = ref<number | null>(null)
const answers = ref<Record<number, number | null>>({})

// Computed
const answeredCount = computed(() => {
  return Object.values(answers.value).filter(answer => answer !== null).length
})

const formattedTime = computed(() => {
  const m = Math.floor(timeLeft.value / 60)
  const s = timeLeft.value % 60
  return `${m}:${s.toString().padStart(2, '0')}`
})

// Methods
onMounted(async () => {
  loading.value = true
  try {
    const res = await quizApi.getQuizDetail(props.quizId)
    if (res.success && res.data) {
      quiz.value = res.data as QuizData
      questions.value = res.data.questions || []
    }
  } catch (error) {
    console.error('Failed to load quiz:', error)
  } finally {
    loading.value = false
  }
})

function startQuiz() {
  started.value = true
  timeLeft.value = (quiz.value?.duration_minutes || 10) * 60

  // Khởi tạo answer rỗng cho mỗi câu hỏi
  answers.value = Object.fromEntries(questions.value.map((q: Question) => [q.id, null]))

  timer.value = window.setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      if (timer.value) clearInterval(timer.value)
      submitQuiz()
    }
  }, 1000)
}

function selectAnswer(questionId: number, optionIndex: number) {
  answers.value[questionId] = optionIndex
}

async function submitQuiz() {
  submitting.value = true
  try {
    // Chuyển đổi answers thành format đúng cho API
    const formattedAnswers = Object.entries(answers.value).map(([questionId, answerIndex]) => ({
      question_id: parseInt(questionId),
      selected_options: answerIndex !== null ? [answerIndex.toString()] : []
    }))

    const payload = {
      attempt_id: 1, // Temporary - should be from startQuizAttempt
      answers: formattedAnswers
    }
    
    const res = await quizApi.submitQuizAttempt(props.quizId, payload)
    if (res.success) {
      alert('Bài làm của bạn đã được nộp!')
      started.value = false
      emit('exit')
    }
  } catch (err) {
    console.error('Submit quiz error:', err)
    alert('Có lỗi xảy ra khi nộp bài!')
  } finally {
    submitting.value = false
    if (timer.value) clearInterval(timer.value)
  }
}

onUnmounted(() => {
  if (timer.value) clearInterval(timer.value)
})
</script>
