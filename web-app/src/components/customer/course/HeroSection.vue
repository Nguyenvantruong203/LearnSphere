<template>
  <section class="relative bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 py-20 px-6 lg:px-20 overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-10 left-10 w-32 h-32 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full blur-3xl"></div>
      <div class="absolute bottom-10 right-10 w-40 h-40 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-orange-300 to-pink-300 rounded-full blur-3xl opacity-5"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto">
      <!-- Welcome Text -->
      <div v-motion
        class="mb-12"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div class="space-y-4">
            <h1 class="text-[#2F327D] text-4xl lg:text-6xl font-bold leading-tight">
              Chào mừng trở lại,<br />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-500">
                sẵn sàng học tiếp chưa?
              </span>
            </h1>
            <p class="text-[#696984] text-lg max-w-2xl">
              Tiếp tục hành trình học tập của bạn với những khóa học chất lượng cao và cập nhật mới nhất
            </p>
          </div>
          
          <div class="flex items-center space-x-4">
            <button class="flex items-center space-x-2 px-6 py-3 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-[#2F327D] hover:text-teal-600 group">
              <i class="fas fa-history group-hover:rotate-12 transition-transform duration-300"></i>
              <span class="font-semibold">Lịch sử học tập</span>
            </button>
            
            <button class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:from-teal-600 hover:to-cyan-600 transform hover:scale-105">
              <i class="fas fa-chart-line"></i>
              <span class="font-semibold">Thống kê tiến độ</span>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Course Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        <div v-for="(course, index) in heroCards" :key="course.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.2, duration: 0.8, type: 'spring' } }">
          <CourseCard 
            :course="course"
            :showProgress="true"
          />
        </div>
      </div>
      
      <!-- Navigation and Stats -->
      <div v-motion
        class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8"
        :initial="{ opacity: 0, y: 30 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }">
        
        <!-- Navigation Arrows -->
        <div class="flex space-x-4">
          <button class="group w-14 h-14 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg flex items-center justify-center hover:bg-white hover:shadow-xl transition-all duration-300 border border-gray-100">
            <i class="fas fa-chevron-left text-xl text-[#2F327D] group-hover:text-teal-500 group-hover:-translate-x-0.5 transition-all duration-300"></i>
          </button>
          <button class="group w-14 h-14 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl shadow-lg flex items-center justify-center hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <i class="fas fa-chevron-right text-xl text-white group-hover:translate-x-0.5 transition-transform duration-300"></i>
          </button>
        </div>
        
        <!-- Quick Stats -->
        <div class="flex items-center space-x-8">
          <div class="text-center">
            <div class="text-2xl font-bold text-[#2F327D] mb-1">3</div>
            <div class="text-sm text-[#696984]">Khóa học đang học</div>
          </div>
          <div class="w-px h-12 bg-gray-200"></div>
          <div class="text-center">
            <div class="text-2xl font-bold text-teal-600 mb-1">15</div>
            <div class="text-sm text-[#696984]">Bài học hoàn thành</div>
          </div>
          <div class="w-px h-12 bg-gray-200"></div>
          <div class="text-center">
            <div class="text-2xl font-bold text-orange-500 mb-1">42h</div>
            <div class="text-sm text-[#696984]">Tổng thời gian học</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Floating elements -->
    <div class="absolute top-20 right-20 opacity-20">
      <div class="w-8 h-8 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
    </div>
    <div class="absolute bottom-32 left-16 opacity-20">
      <div class="w-6 h-6 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
    </div>
    <div class="absolute top-1/3 right-1/4 opacity-20">
      <div class="w-4 h-4 bg-orange-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import CourseCard from './CourseCard.vue'

const heroCards = ref([
  {
    id: 1,
    title: 'AWS Certified Solutions Architect',
    instructor: 'Nguyễn Thị Linh',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2gUZCDMuxr.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/49mzAi8e8L.png',
    progress: 'Bài 5 / 7'
  },
  {
    id: 2,
    title: 'Full Stack Web Development',
    instructor: 'Trần Văn Nam',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/LL5sXfZDoD.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/XJp7XjhP09.png',
    progress: 'Bài 8 / 12'
  },
  {
    id: 3,
    title: 'Digital Marketing Mastery',
    instructor: 'Lê Thị Hương',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/oGbsOPdjq8.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/DrGW9CRWYY.png',
    progress: 'Bài 3 / 10'
  }
])
</script>
