<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center justify-between gap-16">
      <!-- Left Content -->
      <div v-motion
        class="lg:w-1/2"
        :initial="{ opacity: 0, x: -60 }"
        :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }">
        
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] leading-tight mb-6">
          Mọi thứ bạn có thể làm trong lớp học thực tế, 
          <span class="text-[#49BBBD]">bạn đều có thể làm với LearnSphere</span>
        </h2>
        
        <p class="text-lg text-[#696984] leading-relaxed mb-8">
          Phần mềm quản lý trường học của LearnSphere giúp các trường truyền thống và trực tuyến quản lý lịch trình, điểm danh, thanh toán và lớp học ảo tất cả trong một hệ thống đám mây bảo mật.
        </p>
        
        <div class="flex items-center space-x-4">
          <a href="#" class="inline-flex items-center text-[#49BBBD] font-semibold hover:text-[#2F327D] transition-colors group">
            <span>Tìm hiểu thêm</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
      </div>

      <!-- Right Video/Image -->
      <div v-motion
        class="lg:w-1/2 relative"
        :initial="{ opacity: 0, x: 60 }"
        :enter="{ opacity: 1, x: 0, transition: { delay: 0.2, duration: 0.8 } }">
        
        <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
          <!-- Background gradient overlay -->
          <div class="absolute inset-0 bg-gradient-to-br from-teal-600/20 to-blue-600/20 z-10"></div>
          
          <!-- Placeholder image -->
          <div class="w-full h-[400px] bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
            <div class="text-center text-white">
              <i class="fas fa-play-circle text-6xl mb-4 opacity-80"></i>
              <p class="text-lg font-medium">Video Demo</p>
            </div>
          </div>
          
          <!-- Play button overlay -->
          <div class="absolute inset-0 flex items-center justify-center z-20">
            <button @click="playVideo" class="group/play w-20 h-20 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-2xl hover:bg-white hover:scale-110 transition-all duration-300">
              <svg class="w-8 h-8 text-[#23BDEE] ml-1 group-hover/play:scale-110 transition-transform" viewBox="0 0 32 34" fill="none">
                <path d="M30.1429 14.3693C32.1429 15.5224 32.1429 18.4776 30.1429 19.6307L5.14286 33.5738C3.14286 34.7269 0.642856 33.2493 0.642856 30.9431L0.642857 3.05692C0.642857 0.750716 3.14286 -0.726885 5.14286 0.426216L30.1429 14.3693Z" fill="currentColor"/>
              </svg>
            </button>
          </div>
          
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-12 h-12 border-2 border-white/30 rounded-full"></div>
          <div class="absolute bottom-4 left-4 w-8 h-8 bg-white/20 rounded-full"></div>
        </div>
        
        <!-- Floating stats cards -->
        <div v-motion
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
          class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-xl border border-gray-100">
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-users text-white text-lg"></i>
            </div>
            <div>
              <p class="font-bold text-gray-800">10K+</p>
              <p class="text-sm text-gray-600">Học sinh hoạt động</p>
            </div>
          </div>
        </div>
        
        <div v-motion
          :initial="{ opacity: 0, y: -40 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
          class="absolute -top-6 -right-6 bg-white rounded-2xl p-4 shadow-xl border border-gray-100">
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-clock text-white text-lg"></i>
            </div>
            <div>
              <p class="font-bold text-gray-800">24/7</p>
              <p class="text-sm text-gray-600">Hỗ trợ</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { notification } from 'ant-design-vue'

const playVideo = () => {
  notification.info({
    message: 'Video Demo',
    description: 'Tính năng video demo sẽ được triển khai sớm!',
    placement: 'topRight'
  })
}
</script>
