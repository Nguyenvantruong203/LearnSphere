<template>
  <section class="py-24 bg-white">
    <div class="container mx-auto px-6">
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <p class="text-[#525596] font-semibold tracking-widest mb-4 text-sm uppercase">
          ĐÁNH GIÁ KHÁCH HÀNG
        </p>
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Họ nói gì về chúng tôi?
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto leading-relaxed">
          LearnSphere đã nhận được hơn 100k đánh giá tích cực từ người dùng trên khắp thế giới.
        </p>
      </div>

      <div class="relative max-w-6xl mx-auto">
        <!-- Main testimonial card -->
        <div v-motion
          :initial="{ opacity: 0, scale: 0.9 }"
          :enter="{ opacity: 1, scale: 1, transition: { delay: 0.2, duration: 0.8 } }"
          class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
          
          <div class="flex flex-col md:flex-row">
            <!-- Left - Image -->
            <div class="md:w-1/3 relative">
              <div class="absolute inset-4 border-4 border-[#49BBBD] rounded-2xl z-10"></div>
              <div class="relative z-20 p-8">
                <div class="aspect-square bg-gradient-to-br from-teal-400 to-cyan-500 rounded-2xl flex items-center justify-center">
                  <div class="text-center text-white">
                    <div class="text-6xl mb-4">👩‍💼</div>
                    <p class="font-semibold">Gloria Rose</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right - Content -->
            <div class="md:w-2/3 p-8 md:p-12 flex flex-col justify-center">
              <!-- Quote icon -->
              <div class="text-[#49BBBD] mb-6">
                <i class="fas fa-quote-left text-4xl opacity-20"></i>
              </div>
              
              <!-- Testimonial text -->
              <blockquote class="text-xl text-[#5F5F7E] leading-relaxed mb-8 font-light">
                "Cảm ơn rất nhiều vì sự giúp đỡ của bạn. Đây chính xác là những gì tôi đang tìm kiếm. Bạn sẽ không hối hận về điều này. Nó thực sự giúp tôi tiết kiệm thời gian và công sức. LearnSphere chính xác là những gì doanh nghiệp của chúng tôi đang thiếu."
              </blockquote>
              
              <!-- Author info -->
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-2xl font-bold text-[#5F5F7E] mb-2">Gloria Rose</h4>
                  <p class="text-[#80819A] font-medium">Giám đốc Marketing, TechCorp</p>
                </div>
                
                <!-- Rating and reviews -->
                <div class="text-right">
                  <div class="flex items-center justify-end space-x-1 mb-2">
                    <span v-for="i in 5" :key="i" class="text-yellow-400 text-xl">★</span>
                  </div>
                  <p class="text-[#80819A] text-sm">12 đánh giá trên Yelp</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Navigation arrows -->
        <button
          @click="previousTestimonial"
          class="absolute top-1/2 -translate-y-1/2 -left-6 w-12 h-12 bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center text-xl text-[#49BBBD] hover:bg-[#49BBBD] hover:text-white transition-all duration-300 group">
          <i class="fas fa-chevron-left group-hover:-translate-x-0.5 transition-transform"></i>
        </button>
        
        <button
          @click="nextTestimonial"
          class="absolute top-1/2 -translate-y-1/2 -right-6 w-12 h-12 bg-white rounded-full shadow-xl border border-gray-100 flex items-center justify-center text-xl text-[#49BBBD] hover:bg-[#49BBBD] hover:text-white transition-all duration-300 group">
          <i class="fas fa-chevron-right group-hover:translate-x-0.5 transition-transform"></i>
        </button>
        
        <!-- Dots indicator -->
        <div class="flex justify-center mt-8 space-x-3">
          <button 
            v-for="(testimonial, index) in testimonials" 
            :key="index"
            @click="currentIndex = index"
            :class="[
              'w-3 h-3 rounded-full transition-all duration-300',
              currentIndex === index 
                ? 'bg-[#49BBBD] w-8' 
                : 'bg-gray-300 hover:bg-gray-400'
            ]">
          </button>
        </div>
      </div>
      
      <!-- Stats cards -->
      <div v-motion
        :initial="{ opacity: 0, y: 50 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
        class="grid md:grid-cols-3 gap-8 mt-20">
        
        <div class="text-center group">
          <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-users text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-[#2F327D] mb-2">100K+</h3>
          <p class="text-[#696984]">Người dùng hài lòng</p>
        </div>
        
        <div class="text-center group">
          <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-star text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-[#2F327D] mb-2">4.9/5</h3>
          <p class="text-[#696984]">Đánh giá trung bình</p>
        </div>
        
        <div class="text-center group">
          <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-award text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-[#2F327D] mb-2">50+</h3>
          <p class="text-[#696984]">Giải thưởng nhận được</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { notification } from 'ant-design-vue'

interface Testimonial {
  name: string
  position: string
  company: string
  content: string
  rating: number
  reviews: number
  emoji: string
}

const currentIndex = ref(0)

const testimonials = ref<Testimonial[]>([
  {
    name: 'Gloria Rose',
    position: 'Giám đốc Marketing',
    company: 'TechCorp',
    content: 'Cảm ơn rất nhiều vì sự giúp đỡ của bạn. Đây chính xác là những gì tôi đang tìm kiếm. Bạn sẽ không hối hận về điều này. Nó thực sự giúp tôi tiết kiệm thời gian và công sức. LearnSphere chính xác là những gì doanh nghiệp của chúng tôi đang thiếu.',
    rating: 5,
    reviews: 12,
    emoji: '👩‍💼'
  },
  {
    name: 'John Smith',
    position: 'CEO',
    company: 'StartupXYZ',
    content: 'LearnSphere đã thay đổi cách chúng tôi đào tạo nhân viên. Giao diện thân thiện, tính năng phong phú và hỗ trợ tuyệt vời. Tôi khuyên dùng cho mọi doanh nghiệp muốn cải thiện chất lượng đào tạo.',
    rating: 5,
    reviews: 28,
    emoji: '👨‍💻'
  },
  {
    name: 'Sarah Johnson',
    position: 'Trưởng phòng HR',
    company: 'GlobalCorp',
    content: 'Tôi đã thử nhiều nền tảng học trực tuyến, nhưng LearnSphere là tốt nhất. Khả năng theo dõi tiến độ học viên và tính năng báo cáo chi tiết giúp chúng tôi quản lý đào tạo hiệu quả hơn rất nhiều.',
    rating: 5,
    reviews: 45,
    emoji: '👩‍🎓'
  }
])

const nextTestimonial = () => {
  currentIndex.value = (currentIndex.value + 1) % testimonials.value.length
}

const previousTestimonial = () => {
  currentIndex.value = currentIndex.value === 0 ? testimonials.value.length - 1 : currentIndex.value - 1
}
</script>
