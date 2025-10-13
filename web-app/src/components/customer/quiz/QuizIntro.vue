<template>
  <div class="bg-gradient-to-br from-white via-blue-50/30 to-purple-50/40 rounded-3xl p-8 md:p-12 shadow-2xl border border-gray-200/60 text-center min-h-[calc(100vh-120px)] relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-blue-200/20 to-purple-200/20 rounded-full -translate-y-32 translate-x-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-pink-200/20 to-blue-200/20 rounded-full translate-y-24 -translate-x-24"></div>
    
    <div class="relative z-10">
      <!-- 🔹 Hero Icon với animation -->
      <div class="relative w-24 h-24 mx-auto mb-8 group">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl animate-pulse opacity-20"></div>
        <div class="relative w-24 h-24 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-500">
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
        </div>
      </div>

      <div class="mb-10">
        <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-purple-600 bg-clip-text text-transparent mb-3">
          Sẵn sàng làm bài? 🚀
        </h2>
        <p class="text-lg text-gray-600 font-medium">Hãy thử thách bản thân với bài quiz này!</p>
      </div>

      <!-- 🔸 Quiz Info Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 text-left max-w-2xl mx-auto">
        <div class="group flex items-center gap-4 p-6 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl border border-blue-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="font-bold text-gray-800 text-lg">⏱️ Thời gian</p>
            <p class="text-blue-600 font-semibold text-xl">
              {{ quiz.duration_minutes ? `${quiz.duration_minutes} phút` : '∞ Không giới hạn' }}
            </p>
          </div>
        </div>

        <div class="group flex items-center gap-4 p-6 bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl border border-purple-200/50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
          <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <p class="font-bold text-gray-800 text-lg">📝 Số câu hỏi</p>
            <p class="text-purple-600 font-semibold text-xl">{{ quiz.total_questions || 0 }} câu</p>
          </div>
        </div>
      </div>

      <!-- 🔹 Action Button -->
      <div class="flex justify-center mb-12">
        <button
          class="group relative px-10 py-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:shadow-purple-500/25 hover:-translate-y-2 transition-all duration-300 flex items-center gap-3 overflow-hidden"
          @click="$emit('start')"
          :disabled="loading"
        >
          <!-- Background animation -->
          <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          
          <div class="relative z-10 flex items-center gap-3">
            <svg v-if="loading" class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            <svg v-else class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span>{{ loading ? 'Đang tải...' : 'Bắt đầu làm bài ngay!' }}</span>
          </div>
        </button>
      </div>

      <!-- 📜 Lịch sử làm bài -->
      <div v-if="attempts.length" class="text-left mt-10">
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50">
          <h3 class="text-2xl font-bold text-center mb-6 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">📜 Lịch sử làm bài</h3>

          <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border border-gray-200/50 rounded-xl overflow-hidden shadow-sm">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                  <th class="py-4 px-4 text-left">Lần</th>
                  <th class="py-4 px-4 text-left">Điểm</th>
                  <th class="py-4 px-4 text-left">Số đúng</th>
                  <th class="py-4 px-4 text-left">Số sai</th>
                  <th class="py-4 px-4 text-left">Ngày nộp</th>
                  <th class="py-4 px-4 text-center">Xem lại</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="a in attempts" :key="a.id" class="border-t border-gray-200/50 hover:bg-blue-50/50 transition-colors duration-200">
                  <td class="py-4 px-4 font-bold text-gray-800">#{{ a.attempt_no }}</td>
                  <td class="py-4 px-4 text-blue-600 font-bold text-lg">{{ a.score }}/{{ a.max_score }}</td>
                  <td class="py-4 px-4 text-green-600 font-semibold">✅ {{ a.correct_count }}</td>
                  <td class="py-4 px-4 text-red-500 font-semibold">❌ {{ a.wrong_count }}</td>
                  <td class="py-4 px-4 text-gray-600"> <FormatDate :price="a.submitted_at" /></td>
                  <td class="py-4 px-4 text-center">
                    <router-link
                      :to="`/quiz/${quiz.id}/review/${a.id}`"
                      class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200 shadow-sm hover:shadow-md"
                    >
                      👁️ Xem lại
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div v-else class="text-gray-500 mt-8 italic text-base bg-white/40 backdrop-blur-sm rounded-xl p-4 border border-gray-200/50">
        💭 Bạn chưa có lượt làm bài nào trước đây.
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

defineEmits(['start'])

// 🧩 State
const attempts = ref<any[]>([])

// 🧠 Lifecycle
onMounted(() => {
  if (props.quiz?.id) {
    fetchAttempts(props.quiz.id)
  }
})

// 🔁 Theo dõi khi quizId thay đổi
watch(
  () => props.quiz?.id,
  (newId, oldId) => {
    if (newId && newId !== oldId) {
      fetchAttempts(newId)
    }
  }
)

// 🧠 Gọi API lấy lịch sử làm bài
async function fetchAttempts(quizId: number) {
  try {
    const res = await quizApi.getQuizAttempts(quizId)
    if (res.success && res.data) {
      attempts.value = res.data.attempts || []
    } else {
      notification.warning({
        message: 'Không thể tải lịch sử làm bài',
        description: res.message || 'Vui lòng thử lại sau.',
      })
    }
  } catch (err) {
    console.error('❌ Lỗi tải lịch sử:', err)
    notification.error({
      message: 'Lỗi khi tải lịch sử làm bài',
      description: 'Đã xảy ra lỗi không mong muốn.',
    })
  }
}
</script>
