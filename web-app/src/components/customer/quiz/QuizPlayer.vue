<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 flex flex-col">
    <!-- Header -->
    <QuizHeader :title="quiz?.title || 'Quiz'" @exit="$emit('exit')" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="max-w-5xl mx-auto p-6 md:p-8">
        <!-- ⏳ Khi chưa bắt đầu -->
        <QuizIntro v-if="!started" :quiz="quiz || { duration_minutes: 0, total_questions: 0 }" :loading="loading"
          @start="startQuiz" />

        <!-- 🧠 Khi đang làm bài -->
        <div v-else class="space-y-8">
          <!-- Timer & Progress - Enhanced Design -->
          <div
            class="bg-gradient-to-r from-white via-blue-50/30 to-purple-50/30 rounded-3xl p-8 shadow-2xl border border-gray-200/60 backdrop-blur-sm">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
              <!-- Timer Section -->
              <div class="flex items-center gap-6 flex-1">
                <div class="relative">
                  <div
                    class="absolute inset-0 bg-gradient-to-br from-green-400 to-teal-500 rounded-3xl animate-pulse opacity-20">
                  </div>
                  <div
                    class="relative w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-3xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="text-sm text-gray-500 font-medium mb-1">⏰ Thời gian còn lại</p>
                  <p
                    class="text-3xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">
                    {{ formattedTime }}
                  </p>
                </div>
              </div>

              <!-- Progress Section -->
              <div class="flex items-center gap-6 flex-1 justify-end">
                <div class="text-right">
                  <p class="text-sm text-gray-500 font-medium mb-1">📊 Tiến độ hoàn thành</p>
                  <p
                    class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    {{ answeredCount }}/{{ questions.length }}
                  </p>
                </div>
                <div class="relative">
                  <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-3xl animate-pulse opacity-20">
                  </div>
                  <div
                    class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">{{ Math.round((answeredCount / questions.length) * 100)
                    }}%</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Tiến độ</span>
                <span>{{ answeredCount }}/{{ questions.length }} câu đã trả lời</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3 shadow-inner">
                <div
                  class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500 shadow-sm"
                  :style="{ width: `${(answeredCount / questions.length) * 100}%` }"></div>
              </div>
            </div>
          </div>

          <!-- Questions - Enhanced Design -->
          <div v-if="questions.length" class="space-y-8">
            <div v-for="(q, index) in questions" :key="q.id"
              class="group bg-gradient-to-br from-white via-blue-50/20 to-purple-50/20 rounded-3xl p-8 shadow-2xl border border-gray-200/60 hover:shadow-3xl hover:border-blue-300/50 transition-all duration-300 relative overflow-hidden">
              <!-- Background decoration -->
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-100/30 to-purple-100/30 rounded-full -translate-y-16 translate-x-16">
              </div>

              <div class="relative z-10">
                <div class="flex items-start gap-6 mb-8">
                  <div class="relative">
                    <div
                      class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl animate-pulse opacity-20">
                    </div>
                    <div
                      class="relative w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                      <span class="text-white font-bold text-lg">{{ index + 1 }}</span>
                    </div>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 leading-relaxed mb-3">{{ q.text }}</h3>
                    <div class="flex items-center gap-2">
                      <div class="px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full">
                        <p class="text-sm font-medium text-gray-600">
                          {{ q.type === 'multiple_choice' ? '🔘 Chọn nhiều đáp án' :
                            q.type === 'single' ? '🔴 Chọn một đáp án' :
                              '🔘 Chọn một đáp án' }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="space-y-4 ml-18">
                  <div v-for="(opt, i) in q.options" :key="i" @click="selectAnswer(q.id, i)"
                    class="group/option relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    :class="{
                      'border-blue-500 bg-gradient-to-r from-blue-50 to-purple-50 shadow-md': isAnswerSelected(q.id, i),
                      'border-gray-200 hover:border-blue-300 hover:bg-blue-50/30': !isAnswerSelected(q.id, i)
                    }">
                    <div class="flex items-center gap-4">
                      <div class="relative">
                        <div
                          class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                          :class="{
                            'border-blue-500 bg-blue-500 shadow-lg': isAnswerSelected(q.id, i),
                            'border-gray-300 group-hover/option:border-blue-400': !isAnswerSelected(q.id, i)
                          }">
                          <svg v-if="isAnswerSelected(q.id, i)" class="w-4 h-4 text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                              clip-rule="evenodd" />
                          </svg>
                        </div>
                      </div>
                      <div class="flex items-center gap-3 flex-1">
                        <span class="text-lg font-bold text-gray-500 min-w-[32px]">{{ String.fromCharCode(65 + i)
                        }}.</span>
                        <span class="text-gray-800 font-medium text-lg">{{ opt }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Section - Enhanced Design -->
          <div
            class="bg-gradient-to-br from-white via-green-50/20 to-teal-50/20 rounded-3xl p-10 shadow-2xl border border-gray-200/60 text-center relative overflow-hidden">
            <!-- Background decoration -->
            <div
              class="absolute top-0 left-0 w-40 h-40 bg-gradient-to-br from-green-200/20 to-teal-200/20 rounded-full -translate-y-20 -translate-x-20">
            </div>
            <div
              class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-green-200/20 to-blue-200/20 rounded-full translate-y-16 translate-x-16">
            </div>

            <div class="relative z-10">
              <div
                class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>

              <h3
                class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
                🎯 Hoàn thành bài thi
              </h3>
              <div class="mb-8">
                <p class="text-lg text-gray-600 mb-2">Tiến độ hoàn thành của bạn</p>
                <p class="text-2xl font-bold text-green-600">
                  {{ answeredCount }}/{{ questions.length }}
                  <span class="text-lg text-gray-500">({{ Math.round((answeredCount / questions.length) * 100)
                  }}%)</span>
                </p>
              </div>

              <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                  class="px-8 py-3 text-gray-600 border-2 border-gray-300 rounded-2xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 hover:shadow-md"
                  @click="$emit('exit')">
                  ← Thoát khỏi bài thi
                </button>
                <button
                  class="group relative px-10 py-4 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:shadow-green-500/25 hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 overflow-hidden"
                  @click="submitQuiz" :disabled="submitting">
                  <!-- Background animation -->
                  <div
                    class="absolute inset-0 bg-gradient-to-r from-green-600 to-teal-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  </div>

                  <div class="relative z-10 flex items-center gap-3">
                    <svg v-if="submitting" class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <svg v-else class="w-6 h-6 group-hover:scale-110 transition-transform duration-300" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span>{{ submitting ? 'Đang nộp bài...' : '🚀 Nộp bài ngay' }}</span>
                  </div>
                </button>
              </div>
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

// Props & Emits
const props = defineProps<{ quizId: number }>()
const emit = defineEmits(['exit'])

// States
const quiz = ref<Quiz | null>(null)
const questions = ref<Question[]>([])
const loading = ref(false)
const submitting = ref(false)
const started = ref(false)
const timeLeft = ref(0)
const timer = ref<number | null>(null)
const answers = ref<Record<number, number | number[] | null>>({})
const attemptId = ref<number | null>(null)

// Computed
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

// ===== Mounted: Load Quiz Info =====
onMounted(async () => {
  loading.value = true
  try {
    const res = await quizApi.getQuizDetail(props.quizId)
    if (res.success && res.data) {
      quiz.value = res.data.quiz
      questions.value = res.data.questions || []
    } else {
      notification.error({
        message: 'Không thể tải quiz',
        description: res.message || 'Vui lòng thử lại sau.',
      })
    }
  } catch (error) {
    console.error('❌ Failed to load quiz:', error)
    notification.error({
      message: 'Lỗi tải quiz',
      description: 'Đã xảy ra lỗi khi tải thông tin bài quiz. Vui lòng thử lại.',
    })
  } finally {
    loading.value = false
  }
})

// ===== Bắt đầu làm bài =====
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
        message: 'Bắt đầu làm bài 🎯',
        description: `Bạn có ${quiz.value?.duration_minutes || 'không giới hạn'} phút để hoàn thành ${questions.value.length} câu hỏi.`,
      })

      timeLeft.value = (quiz.value?.duration_minutes || 10) * 60
      await nextTick()

      answers.value = Object.fromEntries(questions.value.map((q: Question) => [q.id, null]))

      timer.value = window.setInterval(() => {
        if (timeLeft.value > 0) {
          timeLeft.value--
        } else {
          clearInterval(timer.value!)
          notification.warning({
            message: 'Hết thời gian ⏰',
            description: 'Hệ thống sẽ tự động nộp bài của bạn.',
          })
          submitQuiz()
        }
      }, 1000)
    } else {
      notification.error({
        message: 'Không thể bắt đầu quiz',
        description: res.message || 'Vui lòng thử lại sau.',
      })
    }
  } catch (error: any) {
    console.error('❌ Error starting quiz:', error)
    notification.error({
      message: 'Không thể bắt đầu quiz',
      description: error.message || 'Vui lòng thử lại sau.',
    })
  } finally {
    loading.value = false
  }
}

// ===== Helper Functions =====
function isAnswerSelected(questionId: number, optionIndex: number): boolean {
  const answer = answers.value[questionId]
  if (Array.isArray(answer)) {
    return answer.includes(optionIndex)
  }
  return answer === optionIndex
}

// ===== Chọn đáp án =====
function selectAnswer(questionId: number, optionIndex: number) {
  const question = questions.value.find(q => q.id === questionId)
  if (!question) return

  if (question.type === 'multiple_choice') {
    const current = Array.isArray(answers.value[questionId]) ? answers.value[questionId] as number[] : []
    const exists = current.includes(optionIndex)
    answers.value[questionId] = exists ? current.filter(i => i !== optionIndex) : [...current, optionIndex]
  } else {
    answers.value[questionId] = optionIndex
  }
}

// ===== Nộp bài =====
async function submitQuiz() {
  if (!attemptId.value) {
    notification.warning({
      message: 'Không tìm thấy lượt làm bài!',
      description: 'Vui lòng tải lại trang hoặc bắt đầu lại.',
    })
    return
  }

  submitting.value = true
  try {
    const formattedAnswers = Object.entries(answers.value).map(([questionId, answerValue]) => ({
      question_id: parseInt(questionId),
      selected_options: Array.isArray(answerValue)
        ? answerValue.map(i => i.toString())
        : (answerValue !== null ? [answerValue.toString()] : [])
    }))

    const payload = {
      attempt_id: attemptId.value,
      answers: formattedAnswers,
    }

    const res = await quizApi.submitQuizAttempt(props.quizId, payload)
    if (res.success && res.data) {
      // 🟢 Hiện modal kết quả trung tâm
      Modal.info({
        title: '🎉 Kết quả bài quiz',
        width: 500,
        centered: true,
        okText: 'Xem chi tiết',
        cancelText: 'Đóng',
        closable: true,
        content: h('div', { class: 'space-y-3 text-gray-700 mt-4' }, [
          h('p', { class: 'text-lg font-semibold text-center text-indigo-600' },
            `Điểm: ${res.data.score}/${res.data.max_score}`),
          h('p', { class: 'text-center' },
            `✅ Số câu đúng: ${res.data.correct_count} / ❌ Sai: ${res.data.wrong_count}`),
          h('p', { class: 'text-center text-gray-500 text-sm' },
            `Thời gian nộp: ${new Date(res.data.submitted_at).toLocaleString('vi-VN')}`)
        ]),
        onOk() {

          router.push(`/quiz/${props.quizId}/review/${res.data.attempt_id}`)
        },

        onCancel() {
          emit('exit')
        }
      })

      // ✅ Reset trạng thái
      started.value = false
      if (timer.value) clearInterval(timer.value)
    } else {
      notification.error({
        message: 'Nộp bài thất bại ❌',
        description: res.message || 'Có lỗi xảy ra khi nộp bài.',
      })
    }
  } catch (err) {
    console.error('Submit quiz error:', err)
    notification.error({
      message: 'Lỗi nộp bài ❌',
      description: 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.',
    })
  } finally {
    submitting.value = false
  }
}

// Cleanup
onUnmounted(() => {
  if (timer.value) clearInterval(timer.value)
})
</script>
