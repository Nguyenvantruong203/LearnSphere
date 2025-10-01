<template>
  <div class="group bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 border border-gray-100">
    <!-- Course Image -->
    <div class="relative h-48 overflow-hidden">
      <div :class="course.bgColor" class="w-full h-full flex items-center justify-center">
        <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <i :class="course.icon" class="text-4xl text-white"></i>
        </div>
      </div>
      <!-- Overlay gradient -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>
    
    <!-- Course Content -->
    <div class="p-6">
      <div class="mb-4">
        <h3 class="text-xl font-bold text-[#2F327D] mb-2 group-hover:text-teal-600 transition-colors">
          {{ course.title }}
        </h3>
        <p class="text-gray-500 text-sm">{{ course.lessons }} bài học</p>
      </div>
      
      <!-- Author and Price -->
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full flex items-center justify-center">
            <span class="text-white text-xs font-bold">{{ course.author.charAt(0) }}</span>
          </div>
          <span class="font-semibold text-gray-700 text-sm">{{ course.author }}</span>
        </div>
        
        <div class="text-right">
          <span class="text-xl font-bold text-[#49BBBD]">{{ course.price }}</span>
        </div>
      </div>
      
      <!-- Action Button -->
      <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <button class="w-full py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl font-semibold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105">
          Xem chi tiết
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
export interface Course {
  title: string
  lessons: number
  author: string
  price: string
  icon: string
  bgColor: string
}

defineProps<{
  course: Course
}>()
</script>
