<template>
  <LayoutLearning>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8 px-4 md:px-6">
      <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-white via-blue-50/30 to-purple-50/30 rounded-3xl p-8 shadow-2xl border border-gray-200/60 backdrop-blur-sm mb-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
              <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-3xl animate-pulse opacity-20"></div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-xl">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div>
                <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-purple-600 bg-clip-text text-transparent">
                  📊 Xem lại kết quả Quiz
                </h1>
                <p class="text-lg text-gray-600 font-medium mt-2">Phân tích chi tiết về bài làm của bạn</p>
              </div>
            </div>
            
            <!-- Back Button -->
            <router-link 
              to="/courses" 
              class="group px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-2xl font-semibold hover:from-gray-600 hover:to-gray-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex items-center gap-3"
            >
              <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span class="hidden sm:block">Về khóa học</span>
            </router-link>
          </div>
        </div>

        <!-- Quiz Review Content -->
        <QuizReviewContent :quiz-id="quizId" :attempt-id="attemptId" />
      </div>
    </div>
  </LayoutLearning>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import LayoutLearning from '../layout/layoutLearning.vue'
import QuizReviewContent from '@/components/customer/quiz/QuizReviewContent.vue'

// Lấy quizId và attemptId từ URL
const route = useRoute()
const quizId = Number(route.params.id)
const attemptId = Number(route.params.attemptId)
</script>
