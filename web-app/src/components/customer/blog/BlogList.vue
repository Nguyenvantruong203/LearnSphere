<template>
  <section class="py-24 bg-gradient-to-br from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Danh sách chủ đề Blog
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto">
          Khám phá các chủ đề công nghệ hot nhất và những kiến thức hữu ích cho lập trình viên
        </p>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div 
          v-for="(item, index) in blogItems" 
          :key="item.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }"
          class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer"
        >
          <div class="relative h-64 overflow-hidden">
            <img 
              :src="item.image" 
              :alt="item.title" 
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            >
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-purple-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <!-- Content overlay -->
          <div class="absolute inset-0 flex flex-col justify-end p-6">
            <!-- Category badge -->
            <div class="mb-4">
              <span :class="item.badgeColor" class="px-4 py-2 rounded-full text-sm font-bold backdrop-blur-sm">
                {{ item.title }}
              </span>
            </div>
            
            <!-- Stats -->
            <div class="flex items-center justify-between text-white/80 text-sm mb-2">
              <div class="flex items-center space-x-4">
                <span class="flex items-center space-x-1">
                  <i class="fas fa-newspaper"></i>
                  <span>{{ item.articles }} bài</span>
                </span>
                <span class="flex items-center space-x-1">
                  <i class="fas fa-eye"></i>
                  <span>{{ item.views }}</span>
                </span>
              </div>
            </div>
            
            <!-- Action button (appears on hover) -->
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">
              <button class="w-full mt-3 py-2 bg-white/20 backdrop-blur-md text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 border border-white/20">
                Xem tất cả bài viết
              </button>
            </div>
          </div>
          
          <!-- Decorative corner element -->
          <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
              <i class="fas fa-arrow-right text-white text-sm group-hover:translate-x-0.5 transition-transform duration-300"></i>
            </div>
          </div>
        </div>
      </div>
      
      <!-- View More Categories Button -->
      <div v-motion
        class="text-center mt-16"
        :initial="{ opacity: 0, y: 30 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }">
        <button class="group px-10 py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full font-semibold text-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
          <span class="flex items-center space-x-2">
            <span>Khám phá thêm chủ đề</span>
            <i class="fas fa-compass group-hover:rotate-45 transition-transform duration-300"></i>
          </span>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface BlogCategory {
  id: number
  title: string
  image: string
  articles: number
  views: string
  badgeColor: string
}

const blogItems = ref<BlogCategory[]>([
  {
    id: 1,
    title: 'UI/UX Design',
    image: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    articles: 24,
    views: '15K',
    badgeColor: 'bg-gradient-to-r from-pink-500 to-rose-500 text-white'
  },
  {
    id: 2,
    title: 'React Development',
    image: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    articles: 18,
    views: '22K',
    badgeColor: 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white'
  },
  {
    id: 3,
    title: 'PHP & Laravel',
    image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    articles: 31,
    views: '18K',
    badgeColor: 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white'
  },
  {
    id: 4,
    title: 'JavaScript Tips',
    image: 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    articles: 27,
    views: '28K',
    badgeColor: 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white'
  }
])
</script>
