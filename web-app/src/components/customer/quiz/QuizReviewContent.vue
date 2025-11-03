<template>
  <div class="space-y-6">
    <!-- 🔹 Header kết quả -->
    <div
      v-if="attempt"
      class="bg-gradient-to-br from-white via-[#e8f9f9]/40 to-[#dbf5f5]/40 rounded-3xl shadow-2xl border border-[#aee9e9]/60 p-10 text-center relative overflow-hidden"
    >
      <!-- Background decoration -->
      <div
        class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-[#49bbbd]/20 to-[#65d6d8]/20 rounded-full -translate-y-32 translate-x-32"
      ></div>
      <div
        class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-[#a0e6e7]/20 to-[#49bbbd]/20 rounded-full translate-y-24 -translate-x-24"
      ></div>

      <div class="relative z-10">
        <!-- Icon -->
        <div class="mb-6 flex justify-center items-center gap-3">
          <div
            class="w-12 h-12 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-2xl flex items-center justify-center shadow-xl"
          >
            <svg
              class="w-7 h-7 text-white"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <span
            class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 via-[#49bbbd] to-[#2ea5a8] bg-clip-text text-transparent"
            >Kết quả bài Quiz</span
          >
        </div>

        <!-- Thống kê -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
          <StatCard
            title="📊 Điểm số"
            color="blue"
            :value="`${attempt.score} / ${attempt.max_score}`"
            icon="star"
          />

          <StatCard
            title="✅ Số câu đúng"
            color="green"
            :value="attempt.correct_count"
            icon="check"
          />

          <StatCard
            title="❌ Số câu sai"
            color="red"
            :value="attempt.wrong_count"
            icon="x"
          />

          <StatCard
            title="📅 Ngày nộp"
            color="purple"
            :value="formatDate(attempt.submitted_at)"
            icon="calendar"
          />
        </div>
      </div>
    </div>

    <!-- 🔹 Danh sách câu hỏi -->
    <div v-if="questions.length" class="space-y-8">
      <div class="text-center mb-8">
        <h3
          class="text-3xl font-bold bg-gradient-to-r from-gray-800 via-[#49bbbd] to-[#2ea5a8] bg-clip-text text-transparent mb-2"
        >
          Chi tiết từng câu hỏi
        </h3>
        <p class="text-lg text-gray-600">
          Xem lại đáp án của bạn và đáp án đúng
        </p>
      </div>

      <!-- 🔹 Mỗi câu hỏi -->
      <div
        v-for="(q, index) in questions"
        :key="q.id"
        class="rounded-3xl p-8 shadow-2xl border-2 transition-all duration-300 relative overflow-hidden"
        :class="
          q.is_correct
            ? 'bg-gradient-to-br from-[#ecfbfb] to-[#c6f2f2] border-[#65d6d8]/70'
            : 'bg-gradient-to-br from-[#fff5f5] to-[#ffeaea] border-[#f5b4b4]/70'
        "
      >
        <div class="relative z-10">
          <!-- Header -->
          <div class="flex items-start gap-6 mb-6">
            <div
              class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg text-white"
              :class="q.is_correct ? 'bg-[#49bbbd]' : 'bg-red-500'"
            >
              <svg
                v-if="q.is_correct"
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <svg
                v-else
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </div>

            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl font-bold text-gray-700">{{
                  index + 1
                }}.</span>
                <span
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="
                    q.is_correct
                      ? 'bg-[#d3f6f6] text-[#2ea5a8]'
                      : 'bg-red-100 text-red-700'
                  "
                >
                  {{ q.is_correct ? '✅ Đúng' : '❌ Sai' }}
                </span>
              </div>

              <h3
                class="text-xl md:text-2xl font-bold text-gray-800 leading-relaxed mb-3"
              >
                {{ q.text }}
              </h3>
              <p class="text-sm text-gray-500 italic">
                {{
                  q.type === 'multiple_choice'
                    ? '🔘 Câu hỏi chọn nhiều đáp án'
                    : q.type === 'true_false'
                    ? '⭕️ Đúng / Sai'
                    : '🔴 Chọn một đáp án'
                }}
              </p>
            </div>
          </div>

          <!-- ✅ Thông báo đúng/sai -->
          <div class="mb-6">
            <span
              class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold shadow-sm"
              :class="
                q.is_correct
                  ? 'bg-[#d3f6f6] text-[#2ea5a8] border border-[#65d6d8]'
                  : 'bg-red-100 text-red-700 border border-red-300'
              "
            >
              <svg
                v-if="q.is_correct"
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              {{ q.is_correct ? 'Bạn đã trả lời đúng 🎉' : 'Bạn đã trả lời sai 😢' }}
            </span>
          </div>

          <!-- 🧩 Các đáp án -->
          <div class="space-y-3">
            <div
              v-for="(optText, letter) in q.options"
              :key="letter"
              class="p-4 rounded-xl border-2 flex items-center gap-4 transition-all duration-200"
              :class="{
                'border-[#49bbbd] bg-[#e8f9f9]':
                  q.correct_options.includes(letter),
                'border-red-400 bg-red-50':
                  isSelected(q, letter) &&
                  !q.correct_options.includes(letter),
                'border-gray-200':
                  !isSelected(q, letter) &&
                  !q.correct_options.includes(letter),
              }"
            >
              <div
                class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm border-2"
                :class="{
                  'bg-[#49bbbd] text-white border-[#49bbbd]':
                    q.correct_options.includes(letter),
                  'bg-red-500 text-white border-red-500':
                    isSelected(q, letter) &&
                    !q.correct_options.includes(letter),
                  'bg-gray-200 text-gray-600 border-gray-300':
                    !isSelected(q, letter) &&
                    !q.correct_options.includes(letter),
                }"
              >
                {{ letter }}
              </div>

              <div class="flex-1">
                <p class="font-medium text-gray-800 text-lg">
                  {{ optText }}
                </p>
              </div>
            </div>
          </div>

          <!-- 🔍 Nếu sai, hiển thị đáp án đúng và lựa chọn -->
          <div
            v-if="!q.is_correct"
            class="mt-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-xl text-left"
          >
            <p class="font-semibold text-red-700">
              ✅ Đáp án đúng là:
              <span class="font-bold text-gray-800 ml-1">
                {{
                  q.correct_options
                    .map((letter) => `${letter}. ${q.options[letter]}`)
                    .join(', ')
                }}
              </span>
            </p>
            <p
              v-if="q.selected_options?.length"
              class="text-sm text-gray-600 mt-1"
            >
              ❌ Bạn đã chọn:
              <span class="font-semibold text-gray-800 ml-1">
                {{
                  q.selected_options
                    .map((letter) => `${letter}. ${q.options[letter]}`)
                    .join(', ')
                }}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- 🔹 Nút quay lại -->
    <div class="text-center mt-12">
      <button
        @click="$router.back()"
        class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#49bbbd] to-[#2ea5a8] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:shadow-[#49bbbd]/25 hover:-translate-y-1 transition-all duration-300 gap-3"
      >
        <svg
          class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-300"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          />
        </svg>
        <span>Quay lại</span>
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

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleString('vi-VN')
}

function isSelected(q: any, letter: string) {
  if (!q.selected_options) return false
  return q.selected_options.includes(letter)
}
</script>
