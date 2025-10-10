<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6">
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Tin tức & Tài nguyên mới nhất
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto leading-relaxed">
          Theo dõi những phát triển mới nhất đã xảy ra với LearnSphere trên thế giới
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-12 items-start">
        <!-- Main featured article -->
        <div v-motion
          :initial="{ opacity: 0, x: -60 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.2, duration: 0.8 } }"
          class="space-y-6">
          
          <div class="relative group overflow-hidden rounded-3xl">
            <div class="aspect-video bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
              <div class="text-center text-white">
                <i class="fas fa-newspaper text-6xl mb-4 opacity-80"></i>
                <p class="text-lg font-medium">Tin tức chính</p>
              </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="space-y-4">
            <span class="inline-block px-4 py-2 bg-[#49BBBD] text-white rounded-full text-sm font-semibold">
              TIN TỨC
            </span>
            <h3 class="text-2xl lg:text-3xl font-bold text-[#252641] leading-tight hover:text-[#49BBBD] transition-colors cursor-pointer">
              LearnSphere thêm 30 triệu đô la vào bảng cân đối kế toán cho giải pháp edtech thân thiện với Zoom
            </h3>
            <p class="text-[#696984] leading-relaxed">
              LearnSphere, được ra mắt chưa đầy một năm bởi đồng sáng lập Blackboard Michael Chasen, tích hợp độc quyền với các nền tảng học trực tuyến hàng đầu...
            </p>
            <a href="#" class="inline-flex items-center text-[#49BBBD] font-semibold hover:text-[#2F327D] transition-colors group/link">
              <span>Đọc thêm</span>
              <i class="fas fa-arrow-right ml-2 group-hover/link:translate-x-1 transition-transform"></i>
            </a>
          </div>
        </div>

        <!-- Side articles -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.4, duration: 0.8 } }"
          class="space-y-8">
          
          <div v-for="(article, index) in articles" :key="article.title"
            v-motion
            :initial="{ opacity: 0, y: 30 }"
            :enter="{ opacity: 1, y: 0, transition: { delay: 0.6 + index * 0.1, duration: 0.6 } }"
            class="group flex items-start gap-6 p-4 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-300 cursor-pointer">
            
            <!-- Article image -->
            <div class="flex-shrink-0 w-32 h-24 rounded-xl overflow-hidden">
              <div :class="article.bgColor" class="w-full h-full flex items-center justify-center">
                <i :class="article.icon" class="text-2xl text-white"></i>
              </div>
            </div>
            
            <!-- Article content -->
            <div class="flex-1 space-y-3">
              <span class="inline-block px-3 py-1 bg-[#49BBBD] text-white rounded-full text-xs font-semibold uppercase">
                {{ article.category }}
              </span>
              <h4 class="text-lg font-bold text-[#252641] leading-tight group-hover:text-[#49BBBD] transition-colors line-clamp-2">
                {{ article.title }}
              </h4>
              <div class="flex items-center space-x-4 text-sm text-[#696984]">
                <span class="flex items-center space-x-1">
                  <i class="fas fa-calendar-alt"></i>
                  <span>{{ article.date }}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Call to action -->
      <div v-motion
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
        class="text-center mt-16">
        <button class="group px-10 py-4 bg-gradient-to-r from-[#49BBBD] to-cyan-500 text-white rounded-full font-semibold text-lg hover:from-[#2F327D] hover:to-[#49BBBD] transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
          <router-link to="/blog" class="flex items-center space-x-2">
            <span>Xem tất cả tin tức</span>
            <i class="fas fa-newspaper group-hover:rotate-12 transition-transform duration-300"></i>
          </router-link>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Article {
  title: string
  category: string
  date: string
  icon: string
  bgColor: string
}

const articles = ref<Article[]>([
  {
    title: 'Class Technologies Inc. hoàn thành vòng gọi vốn Series A trị giá 30 triệu đô la',
    category: 'BÁO CHÍ',
    date: '15 Tháng 9, 2024',
    icon: 'fas fa-dollar-sign',
    bgColor: 'bg-gradient-to-br from-green-400 to-emerald-500'
  },
  {
    title: 'Các nhà đầu tư sớm nhất của Zoom đang đầu tư hàng triệu vào một Zoom tốt hơn cho trường học',
    category: 'TIN TỨC',
    date: '10 Tháng 9, 2024',
    icon: 'fas fa-video',
    bgColor: 'bg-gradient-to-br from-blue-400 to-cyan-500'
  },
  {
    title: 'Cựu CEO Blackboard huy động 16 triệu đô la để đưa tính năng LMS vào lớp học Zoom',
    category: 'TIN TỨC',
    date: '5 Tháng 9, 2024',
    icon: 'fas fa-chalkboard-teacher',
    bgColor: 'bg-gradient-to-br from-purple-400 to-pink-500'
  },
  {
    title: 'LearnSphere công bố tính năng AI mới giúp cá nhân hóa trải nghiệm học tập',
    category: 'CÔNG NGHỆ',
    date: '1 Tháng 9, 2024',
    icon: 'fas fa-robot',
    bgColor: 'bg-gradient-to-br from-orange-400 to-red-500'
  }
]);
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
