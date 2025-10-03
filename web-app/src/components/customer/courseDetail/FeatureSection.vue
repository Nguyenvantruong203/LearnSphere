<template>
  <section class="py-16 px-8 bg-gradient-to-br from-teal-50 to-cyan-50 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-teal-200/30 to-transparent rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-cyan-200/30 to-transparent rounded-full translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div class="space-y-8">
          <div class="flex items-center space-x-4 mb-8">
            <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
              <i class="fas fa-play text-white text-2xl"></i>
            </div>
            <div class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-md">
              <i class="fas fa-graduation-cap text-teal-600 text-xl"></i>
            </div>
          </div>
          
          <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] leading-tight">
            Mọi thứ bạn có thể làm trong lớp học truyền thống, bạn đều có thể làm với 
            <span class="text-transparent bg-gradient-to-r from-teal-500 to-cyan-500 bg-clip-text">LearnSphere</span>
          </h2>
          
          <p class="text-xl text-[#696984] leading-relaxed">
            Hệ thống quản lý học tập của LearnSphere giúp các trường học truyền thống và trực tuyến quản lý lịch học, điểm danh, thanh toán và lớp học ảo - tất cả trong một hệ thống đám mây an toàn.
          </p>
          
          <div class="flex flex-wrap gap-4">
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">Học tập linh hoạt</span>
            </div>
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">Hỗ trợ 24/7</span>
            </div>
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-certificate text-purple-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">Chứng chỉ uy tín</span>
            </div>
          </div>
          
          <button class="inline-flex items-center space-x-3 text-teal-600 font-semibold text-lg hover:text-teal-700 transition-colors duration-300 group">
            <span>Tìm hiểu thêm</span>
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
          </button>
        </div>

        <!-- Right Content -->
        <div class="relative">
          <div class="relative z-10">
            <!-- Main feature image -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100 mb-6">
              <img 
                src="https://static.codia.ai/image/2025-10-02/VUBKJjj07n.png" 
                alt="Learning Platform" 
                class="w-full h-64 object-cover rounded-2xl"
              />
              
              <!-- Feature badges -->
              <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl p-4 text-center">
                  <div class="text-2xl font-bold text-teal-600 mb-1">10K+</div>
                  <div class="text-sm text-[#696984]">Học viên</div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 text-center">
                  <div class="text-2xl font-bold text-purple-600 mb-1">500+</div>
                  <div class="text-sm text-[#696984]">Khóa học</div>
                </div>
              </div>
            </div>
            
            <!-- Floating elements -->
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl shadow-lg flex items-center justify-center transform rotate-12">
              <i class="fas fa-star text-white text-2xl"></i>
            </div>
            
            <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-gradient-to-r from-green-400 to-teal-400 rounded-2xl shadow-lg flex items-center justify-center transform -rotate-12">
              <i class="fas fa-rocket text-white text-xl"></i>
            </div>
          </div>
          
          <!-- Background decorative elements -->
          <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-teal-200/20 to-cyan-200/20 rounded-full -z-10"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// No additional logic needed for this component
</script>
