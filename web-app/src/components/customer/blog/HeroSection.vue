<template>
  <section class="relative bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 py-24 overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-20 left-20 w-40 h-40 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 right-20 w-32 h-32 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-indigo-300 to-purple-300 rounded-full blur-3xl opacity-5"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-20">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Left Content -->
        <div v-motion
          :initial="{ opacity: 0, x: -60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }">
          
          <div class="flex items-center space-x-3 mb-6">
            <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm rounded-full px-4 py-2 shadow-lg">
              <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                <i class="fas fa-pen-fancy text-white text-sm"></i>
              </div>
              <span class="text-sm font-semibold text-[#2F327D]">Tác giả: LearnSphere Team</span>
            </div>
            <div class="bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 px-4 py-2 rounded-full text-sm font-semibold">
              Công nghệ
            </div>
          </div>
          
          <h1 class="text-4xl lg:text-6xl font-bold text-[#2F327D] mb-6 leading-tight">
            Tại sao <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">SwiftUI</span> nên được mọi 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-500">Mobile Developer</span> chú ý?
          </h1>
          
          <p class="text-lg text-[#696984] mb-8 leading-relaxed">
            Khám phá framework UI declarative của Apple và cách nó đang thay đổi cách chúng ta xây dựng ứng dụng iOS. 
            Tìm hiểu về các tính năng mạnh mẽ và lợi ích của SwiftUI trong phát triển ứng dụng hiện đại.
          </p>
          
          <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <button class="group px-8 py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold text-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
              <span class="flex items-center space-x-2">
                <span>Bắt đầu học ngay</span>
                <i class="fas fa-rocket group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300"></i>
              </span>
            </button>
            
            <button class="group px-8 py-4 bg-white/80 backdrop-blur-sm text-[#2F327D] rounded-xl font-semibold text-lg hover:bg-white transition-all duration-300 border border-gray-200 hover:shadow-lg">
              <span class="flex items-center space-x-2">
                <i class="fas fa-play-circle group-hover:scale-110 transition-transform duration-300"></i>
                <span>Xem demo</span>
              </span>
            </button>
          </div>
        </div>

        <!-- Right Image -->
        <div v-motion
          :initial="{ opacity: 0, x: 60, scale: 0.9 }"
          :enter="{ opacity: 1, x: 0, scale: 1, transition: { duration: 1, type: 'spring' } }"
          class="relative">
          
          <div class="relative group">
            <div class="absolute -inset-4 bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 rounded-3xl blur-2xl opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
              <img 
                src="https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                alt="Mobile Development with SwiftUI" 
                class="w-full h-auto transform group-hover:scale-105 transition-transform duration-500"
              >
              <!-- Overlay with play button -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <button class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-white/30 hover:bg-white/30 transition-all duration-300">
                  <i class="fas fa-play text-white text-2xl ml-1"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Floating stats cards -->
          <div v-motion
            :initial="{ opacity: 0, y: 40 }"
            :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
            class="absolute -bottom-6 -left-6 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-eye text-white text-lg"></i>
              </div>
              <div>
                <div class="text-lg font-bold text-[#2F327D]">125K+</div>
                <div class="text-sm text-[#696984]">Lượt xem</div>
              </div>
            </div>
          </div>

          <div v-motion
            :initial="{ opacity: 0, y: -40 }"
            :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
            class="absolute -top-6 -right-6 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-heart text-white text-lg"></i>
              </div>
              <div>
                <div class="text-lg font-bold text-[#2F327D]">98%</div>
                <div class="text-sm text-[#696984]">Yêu thích</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating elements -->
    <div class="absolute top-32 right-32 opacity-20">
      <div class="w-6 h-6 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
    </div>
    
    <div class="absolute bottom-40 left-32 opacity-20">
      <div class="w-4 h-4 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
    </div>

    <div class="absolute top-1/2 left-20 opacity-20">
      <div class="w-8 h-8 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
    </div>
  </section>
</template>

<script setup lang="ts">
// No imports needed for this component
</script>
