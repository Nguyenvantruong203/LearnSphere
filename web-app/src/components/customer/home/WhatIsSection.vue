<template>
  <section class="py-24 bg-white">
    <div class="container mx-auto px-6 text-center">
      <div v-motion
        class="mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          LearnSphere là gì?
        </h2>
        <p class="text-xl text-[#696984] max-w-5xl mx-auto leading-relaxed">
          LearnSphere là nền tảng cho phép các nhà giáo dục tạo lớp học trực tuyến, nơi họ có thể lưu trữ tài liệu khóa học trực tuyến; quản lý bài tập, bài kiểm tra và kỳ thi; theo dõi thời hạn; chấm điểm kết quả và cung cấp phản hồi cho học sinh tất cả trong một nơi.
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- For Instructors -->
        <div v-motion
          :initial="{ opacity: 0, x: -60 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.2, duration: 0.8 } }"
          class="group relative rounded-3xl overflow-hidden h-96 shadow-xl hover:shadow-2xl transition-all duration-500">
          
          <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-orange-500"></div>
          <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
          
          <!-- Content overlay -->
          <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-white">
            <div class="mb-6 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
              <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
            
            <h3 class="text-3xl font-bold mb-6">DÀNH CHO GIẢNG VIÊN</h3>
            
            <button class="group/btn px-8 py-4 rounded-full border-2 border-white text-white font-semibold hover:bg-white hover:text-orange-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
              <span class="flex items-center space-x-2">
                <span>Bắt đầu lớp học ngày hôm nay</span>
                <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
              </span>
            </button>
          </div>
          
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-12 h-12 border-2 border-white/30 rounded-full opacity-50"></div>
          <div class="absolute bottom-4 left-4 w-8 h-8 bg-white/20 rounded-full opacity-50"></div>
        </div>

        <!-- For Students -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.4, duration: 0.8 } }"
          class="group relative rounded-3xl overflow-hidden h-96 shadow-xl hover:shadow-2xl transition-all duration-500">
          
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-teal-500"></div>
          <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
          
          <!-- Content overlay -->
          <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-white">
            <div class="mb-6 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
              <i class="fas fa-graduation-cap text-3xl"></i>
            </div>
            
            <h3 class="text-3xl font-bold mb-6">DÀNH CHO HỌC SINH</h3>
            
            <button class="group/btn px-8 py-4 rounded-full bg-[#23BDEE]/90 hover:bg-[#23BDEE] text-white font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
              <span class="flex items-center space-x-2">
                <span>Nhập mã truy cập</span>
                <i class="fas fa-key group-hover/btn:rotate-12 transition-transform"></i>
              </span>
            </button>
          </div>
          
          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-12 h-12 border-2 border-white/30 rounded-full opacity-50"></div>
          <div class="absolute bottom-4 left-4 w-8 h-8 bg-white/20 rounded-full opacity-50"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// Component không cần logic phức tạp
</script>
