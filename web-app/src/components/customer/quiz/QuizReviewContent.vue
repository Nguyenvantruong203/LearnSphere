<template>
  <div class="space-y-8">
    <!-- Header Result - Enhanced Design -->
    <div v-if="attempt" class="bg-gradient-to-br from-white via-blue-50/20 to-purple-50/20 rounded-3xl shadow-2xl border border-gray-200/60 p-10 text-center relative overflow-hidden">
      <!-- Background decoration -->
      <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-blue-200/20 to-purple-200/20 rounded-full -translate-y-32 translate-x-32"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-green-200/20 to-blue-200/20 rounded-full translate-y-24 -translate-x-24"></div>
      
      <div class="relative z-10">
        <!-- Trophy Icon -->
        <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
          </svg>
        </div>

        <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-purple-600 bg-clip-text text-transparent mb-8">
          🎉 Kết quả bài quiz
        </h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
          <!-- Score -->
          <div class="group bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 border border-blue-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium mb-1">📊 Điểm số</p>
            <p class="text-3xl font-bold text-blue-600">
              {{ attempt.score }} / {{ attempt.max_score }}
            </p>
          </div>

          <!-- Correct Count -->
          <div class="group bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium mb-1">✅ Số câu đúng</p>
            <p class="text-3xl font-bold text-green-600">{{ attempt.correct_count }}</p>
          </div>

          <!-- Wrong Count -->
          <div class="group bg-gradient-to-br from-red-50 to-rose-100 rounded-2xl p-6 border border-red-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium mb-1">❌ Số câu sai</p>
            <p class="text-3xl font-bold text-red-500">{{ attempt.wrong_count }}</p>
          </div>

          <!-- Submit Date -->
          <div class="group bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl p-6 border border-purple-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium mb-1">📅 Ngày nộp</p>
            <p class="text-lg font-bold text-purple-600">
              {{ formatDate(attempt.submitted_at) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Questions Review - Enhanced Design -->
    <div v-if="questions.length" class="space-y-8">
      <div class="text-center mb-8">
        <h3 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
          📝 Chi tiết từng câu hỏi
        </h3>
        <p class="text-lg text-gray-600">Xem lại đáp án của bạn và đáp án đúng</p>
      </div>

      <div v-for="(q, index) in questions" :key="q.id"
        class="group bg-gradient-to-br from-white via-gray-50/20 to-blue-50/20 rounded-3xl p-8 shadow-2xl border border-gray-200/60 hover:shadow-3xl transition-all duration-300 relative overflow-hidden">
        
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-gray-100/30 to-blue-100/30 rounded-full -translate-y-16 translate-x-16"></div>
        
        <div class="relative z-10">
          <!-- Question Header -->
          <div class="flex items-start gap-6 mb-8">
            <div class="relative">
              <div class="absolute inset-0 rounded-2xl animate-pulse opacity-20"
                :class="q.is_correct ? 'bg-green-400' : 'bg-red-400'"></div>
              <div class="relative w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300"
                :class="q.is_correct ? 'bg-green-500' : 'bg-red-500'">
                <svg v-if="q.is_correct" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            </div>

            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl font-bold text-gray-700">{{ index + 1 }}.</span>
                <div class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="q.is_correct ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                  {{ q.is_correct ? '✅ Đúng' : '❌ Sai' }}
                </div>
              </div>
              
              <h3 class="text-xl md:text-2xl font-bold text-gray-800 leading-relaxed mb-3">
                {{ q.text }}
              </h3>
              
              <div class="flex items-center gap-2">
                <div class="px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full">
                  <p class="text-sm font-medium text-gray-600">
                    {{ q.type === 'multi' ? '🔘 Chọn nhiều đáp án' : 
                        q.type === 'single' ? '🔴 Chọn một đáp án' : 
                        '🔘 Chọn một đáp án' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Options -->
          <div class="space-y-4 ml-18">
            <div v-for="(opt, i) in q.options" :key="i"
              class="relative p-5 rounded-2xl border-2 transition-all duration-300 flex items-center gap-4"
              :class="optionClass(q, i)">
              
              <!-- Status Icons -->
              <div class="relative">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm border-2" 
                  :class="{
                    'bg-green-500 text-white border-green-500': q.correct_options.includes(String.fromCharCode(65 + i)),
                    'bg-red-500 text-white border-red-500': isSelected(q, i) && !q.correct_options.includes(String.fromCharCode(65 + i)),
                    'bg-gray-200 text-gray-600 border-gray-300': !isSelected(q, i) && !q.correct_options.includes(String.fromCharCode(65 + i))
                  }">
                  {{ String.fromCharCode(65 + i) }}
                </div>
                
                <!-- Correct/Wrong indicator -->
                <div v-if="q.correct_options.includes(String.fromCharCode(65 + i))" 
                  class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                  <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                
                <div v-else-if="isSelected(q, i)" 
                  class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                  <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>

              <div class="flex-1">
                <div class="flex items-center gap-3">
                  <span class="font-medium text-gray-800 text-lg">{{ opt }}</span>
                  
                  <!-- Status Labels -->
                  <div class="flex gap-2">
                    <span v-if="q.correct_options.includes(String.fromCharCode(65 + i))" 
                      class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                      ✅ Đáp án đúng
                    </span>
                    <span v-if="isSelected(q, i)" 
                      class="px-2 py-1 text-xs font-semibold rounded-full"
                      :class="q.correct_options.includes(String.fromCharCode(65 + i)) 
                        ? 'bg-blue-100 text-blue-700' 
                        : 'bg-red-100 text-red-700'">
                      {{ q.correct_options.includes(String.fromCharCode(65 + i)) ? '👍 Bạn chọn đúng' : '👎 Bạn chọn sai' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Back Button - Enhanced Design -->
    <div class="text-center mt-12">
      <router-link 
        to="/courses" 
        class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:shadow-indigo-500/25 hover:-translate-y-1 transition-all duration-300 gap-3"
      >
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Quay lại khóa học</span>
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { quizApi } from '@/api/customer/quizApi'
import { notification } from 'ant-design-vue'
import { useRoute } from 'vue-router'

// Route params
const route = useRoute()
const quizId = Number(route.params.id)
const attemptId = Number(route.params.attemptId)

// States
const attempt = ref<any>(null)
const questions = ref<any[]>([])
const loading = ref(false)

// Lifecycle
onMounted(async () => {
  await fetchReview()
})

// ===== Fetch Quiz Review =====
async function fetchReview() {
  loading.value = true
  try {
    const res = await quizApi.getQuizReview(quizId, attemptId)
    if (res.success && res.data) {
      attempt.value = res.data.attempt
      questions.value = res.data.questions || []
    } else {
      notification.error({
        message: 'Không thể tải dữ liệu review',
        description: res.message || 'Vui lòng thử lại sau.',
      })
    }
  } catch (err) {
    console.error('❌ Lỗi tải review:', err)
    notification.error({
      message: 'Lỗi tải dữ liệu review',
      description: 'Đã xảy ra lỗi không mong muốn.',
    })
  } finally {
    loading.value = false
  }
}

// ===== Helper: Format datetime =====
function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleString('vi-VN')
}

// ===== Helper: xác định class của từng lựa chọn =====
function optionClass(q: any, i: number) {
  const letter = String.fromCharCode(65 + i)
  const isCorrect = q.correct_options.includes(letter)
  const isChosen = isSelected(q, i)

  if (isCorrect) return 'border-green-500 bg-green-50'
  if (isChosen && !isCorrect) return 'border-red-400 bg-red-50'
  return 'border-gray-200'
}

// ===== Helper: kiểm tra xem học viên có chọn option này không =====
function isSelected(q: any, i: number) {
  if (!q.selected_options) return false
  const letter = String.fromCharCode(65 + i)
  return q.selected_options.includes(letter)
}
</script>

<style scoped>
.bg-info {
  background-color: #eaf5ff;
}
</style>
