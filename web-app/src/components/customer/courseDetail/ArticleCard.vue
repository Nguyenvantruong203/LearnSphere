<template>
  <div class="group block bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
    <!-- Course Image -->
    <div class="relative overflow-hidden">
      <img 
        :src="image" 
        :alt="title" 
        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
      />
      <!-- Overlay gradient -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      
      <!-- Category badge -->
      <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md rounded-xl px-3 py-1 shadow-lg">
        <div class="flex items-center space-x-2">
          <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
          <span class="text-gray-800 text-sm font-medium">{{ category }}</span>
        </div>
      </div>
      
      <!-- Duration badge -->
      <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
        <ClockCircleOutlined class="mr-1" />{{ duration }}
      </div>
    </div>
    
    <!-- Course Content -->
    <div class="p-6 space-y-4">
      <!-- Title -->
      <h3 class="text-[#2F327D] text-xl font-bold leading-tight group-hover:text-teal-600 transition-colors duration-300 line-clamp-2">
        {{ title }}
      </h3>
      
      <!-- Description -->
      <p class="text-[#696984] text-sm leading-relaxed line-clamp-2">
        {{ description }}
      </p>
      
      <!-- Instructor -->
      <div class="flex items-center space-x-3">
        <div class="relative">
          <img 
            :src="avatar" 
            :alt="author" 
            class="w-10 h-10 rounded-full object-cover shadow-md"
          />
          <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
        </div>
        <div>
          <span class="text-gray-800 text-sm font-semibold block">{{ author }}</span>
          <span class="text-gray-500 text-xs">Giảng viên</span>
        </div>
      </div>
      
      <!-- Price and Action -->
      <div class="flex items-center justify-between pt-2">
        <div class="space-y-1">
          <div class="flex items-center space-x-2">
            <span class="text-teal-600 text-2xl font-bold">{{ currentPrice }}</span>
            <span class="text-gray-400 text-sm line-through">{{ originalPrice }}</span>
          </div>
          <div class="flex items-center space-x-1 text-yellow-400">
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <span class="text-gray-500 text-xs ml-1">(4.9)</span>
          </div>
        </div>
        
        <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:from-teal-600 hover:to-cyan-600 transform hover:scale-105 shadow-lg">
          Xem chi tiết
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ClockCircleOutlined } from '@ant-design/icons-vue'

interface Props {
  image: string
  category: string
  title: string
  description: string
  author: string
  avatar: string
  originalPrice: string
  currentPrice: string
  duration: string
}

defineProps<Props>()
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
