<template>
  <section class="pt-32 pb-20">
    <div class="container mx-auto px-6 text-center">
      <div v-motion
        class="mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          The All-in-One Cloud Learning Platform
        </h2>
        <p class="text-xl text-[#696984] max-w-4xl mx-auto leading-relaxed">
          LearnSphere brings together every tool you need to build, sell, and manage online courses — 
          from video lessons and quizzes to payments, analytics, and community engagement — all in one secure platform.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-10">
        <div v-for="(feature, index) in features" :key="feature.title"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.2, duration: 0.8, type: 'spring' } }"
          class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl text-center transition-all duration-500 hover:-translate-y-2 border border-gray-100">
          
          <div class="relative mb-8">
            <div :class="[feature.bgColor, 'w-24 h-24 rounded-2xl mx-auto flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg']">
              <i :class="feature.icon" class="text-3xl text-white"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <h3 class="text-2xl font-bold text-[#2F327D] mb-4 group-hover:text-teal-600 transition-colors">
            {{ feature.title }}
          </h3>
          <p class="text-[#696984] leading-relaxed">
            {{ feature.description }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const features = ref([
  {
    title: 'Smart Course Management',
    description:
      'Easily create, organize, and update your online courses with videos, quizzes, flashcards, and certificates — all synchronized in one place.',
    icon: 'fas fa-graduation-cap',
    bgColor: 'bg-gradient-to-br from-blue-500 to-blue-600',
  },
  {
    title: 'Seamless Payments & Enrollment',
    description:
      'Accept secure payments, manage orders, and provide instant access to paid or free courses with automated enrollment tracking.',
    icon: 'fas fa-credit-card',
    bgColor: 'bg-gradient-to-br from-teal-500 to-cyan-600',
  },
  {
    title: 'Interactive Learning Community',
    description:
      'Enable learners to chat with instructors, discuss lessons, and share experiences through integrated messaging and community blogs.',
    icon: 'fas fa-comments',
    bgColor: 'bg-gradient-to-br from-purple-500 to-indigo-600',
  },
])
</script>
