<template>
  <LayoutLearning>
    <div class="min-h-screen bg-gradient-to-br from-[#e8f9f9] via-[#f3fdfd] to-[#f9fefe] py-6 px-4 md:px-8">
      <div class="max-w-6xl mx-auto">
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-10 shadow-2xl border border-teal-100 mb-8">
          <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-7">
              <div class="relative">
                <div
                  class="absolute inset-0 bg-gradient-to-br from-[#49bbbd] to-[#2ea5a8] rounded-3xl animate-pulse opacity-25">
                </div>
                <div
                  class="relative w-18 h-18 bg-gradient-to-br from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8] rounded-3xl flex items-center justify-center shadow-2xl">
                  <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

              <div>
                <h1
                  class="text-4xl md:text-5xl font-black bg-gradient-to-r from-gray-800 via-[#2ea5a8] to-[#49bbbd] bg-clip-text text-transparent leading-tight">
                  Quiz Review
                </h1>
                <p class="text-xl text-gray-600 font-medium mt-3">
                  Detailed analysis of your performance
                </p>
              </div>
            </div>

            <button @click="$router.back()"
              class="group flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#49bbbd] via-[#2ea5a8] to-[#68d6d8] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
              <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span class="hidden sm:inline">Back to Course</span>
              <span class="sm:hidden">Back</span>
            </button>
          </div>
        </div>

        <QuizReviewContent :quiz-id="quizId" :attempt-id="attemptId" />
      </div>
    </div>
  </LayoutLearning>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import LayoutLearning from '../layout/layoutLearning.vue'
import QuizReviewContent from '@/components/customer/quiz/QuizReviewContent.vue'

const route = useRoute()
const quizId = Number(route.params.id)
const attemptId = Number(route.params.attemptId)
</script>