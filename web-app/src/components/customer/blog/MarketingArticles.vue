<template>
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <div v-motion
        class="flex justify-between items-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <div>
          <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-2">Bài viết Marketing</h2>
          <p class="text-lg text-[#696984]">Khám phá chiến lược marketing hiệu quả</p>
        </div>
        <a href="#" class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
          <span>Xem tất cả</span>
          <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <article 
          v-for="(article, index) in articles" 
          :key="article.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }"
          class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100"
        >
          <div class="relative overflow-hidden">
            <img 
              :src="article.image" 
              :alt="article.title" 
              class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
            >
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Category badge -->
            <div class="absolute top-3 left-3">
              <span :class="article.categoryColor" class="px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                {{ article.category }}
              </span>
            </div>
            
            <!-- Duration badge -->
            <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full font-medium">
              <i class="fas fa-clock mr-1"></i>{{ article.duration }}
            </div>
            
            <!-- Play button overlay -->
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <button class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-white/30 hover:bg-white/30 transition-all duration-300">
                <i class="fas fa-play text-white text-xl ml-1"></i>
              </button>
            </div>
          </div>
          
          <div class="p-6">
            <h3 class="text-lg font-bold text-[#2F327D] mb-3 leading-tight group-hover:text-teal-600 transition-colors duration-300 line-clamp-2">
              {{ article.title }}
            </h3>
            
            <p class="text-[#696984] text-sm mb-4 leading-relaxed line-clamp-2">
              {{ article.description }}
            </p>
            
            <!-- Author info -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-2">
                <img 
                  :src="article.author.avatar" 
                  :alt="article.author.name" 
                  class="w-8 h-8 rounded-full object-cover shadow-md"
                >
                <span class="text-sm text-gray-700 font-medium">{{ article.author.name }}</span>
              </div>
              
              <div class="flex items-center space-x-1 text-yellow-400">
                <i class="fas fa-star text-xs"></i>
                <span class="text-xs text-gray-600 font-medium">{{ article.rating }}</span>
              </div>
            </div>
            
            <!-- Price and action -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
              <span class="text-2xl font-bold text-teal-600">{{ article.price }}</span>
              
              <button class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:from-teal-600 hover:to-cyan-600 transform hover:scale-105 shadow-lg">
                Xem khóa học
              </button>
            </div>
          </div>
        </article>
      </div>
      
      <!-- Featured Banner -->
      <div v-motion
        :initial="{ opacity: 0, y: 50 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
        class="mt-16 bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden">
        
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-10 right-10 w-32 h-32 bg-white rounded-full blur-2xl"></div>
          <div class="absolute bottom-10 left-10 w-24 h-24 bg-white rounded-full blur-xl"></div>
        </div>
        
        <div class="relative z-10 text-center">
          <h3 class="text-3xl lg:text-4xl font-bold mb-4">
            🚀 Trở thành chuyên gia Marketing số
          </h3>
          <p class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
            Học từ những chuyên gia hàng đầu và nắm vững các chiến lược marketing hiệu quả nhất
          </p>
          <button class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-xl">
            Khám phá ngay
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Article {
  id: number
  title: string
  category: string
  categoryColor: string
  duration: string
  image: string
  description: string
  author: {
    name: string
    avatar: string
  }
  rating: string
  price: string
}

const articles = ref<Article[]>([
  {
    id: 1,
    title: 'Digital Marketing Strategy cho Startup',
    category: 'Marketing',
    categoryColor: 'bg-gradient-to-r from-orange-500 to-red-500 text-white',
    duration: '3 tháng',
    image: 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    description: 'Học cách xây dựng chiến lược marketing số hiệu quả cho startup từ A đến Z với các case study thực tế',
    author: {
      name: 'Nguyễn Thị Linh',
      avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
    },
    rating: '4.9',
    price: '2.000.000đ'
  },
  {
    id: 2,
    title: 'Social Media Marketing Mastery',
    category: 'Content',
    categoryColor: 'bg-gradient-to-r from-pink-500 to-purple-500 text-white',
    duration: '2 tháng',
    image: 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    description: 'Nắm vững nghệ thuật marketing trên các nền tảng mạng xã hội để tăng engagement và doanh số',
    author: {
      name: 'Trần Văn Nam',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
    },
    rating: '4.8',
    price: '1.800.000đ'
  },
  {
    id: 3,
    title: 'Email Marketing & Automation',
    category: 'Automation',
    categoryColor: 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white',
    duration: '1.5 tháng',
    image: 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    description: 'Xây dựng hệ thống email marketing tự động để nuôi dưỡng leads và tăng tỷ lệ chuyển đổi',
    author: {
      name: 'Lê Thị Hương',
      avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
    },
    rating: '4.7',
    price: '1.500.000đ'
  },
  {
    id: 4,
    title: 'Google Ads & Facebook Ads Pro',
    category: 'Quảng cáo',
    categoryColor: 'bg-gradient-to-r from-green-500 to-emerald-500 text-white',
    duration: '2.5 tháng',
    image: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    description: 'Master các nền tảng quảng cáo trả phí để tối ưu hóa ROI và scale business hiệu quả',
    author: {
      name: 'Phạm Minh Tuấn',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
    },
    rating: '4.9',
    price: '2.500.000đ'
  }
])
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
