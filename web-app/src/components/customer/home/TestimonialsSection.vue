<template>
  <section class="py-24 bg-white">
    <div class="container mx-auto px-6">
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        
        <p class="text-[#525596] font-semibold tracking-widest mb-4 text-sm uppercase">
          CUSTOMER REVIEWS
        </p>
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          What do they say about us?
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto leading-relaxed">
          LearnSphere has received positive feedback from users around the world.
        </p>
      </div>

      <div class="relative max-w-6xl mx-auto">
        <!-- 🔹 Thẻ đánh giá chính -->
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
                    <div class="text-6xl mb-4">{{ currentTestimonial.emoji }}</div>
                    <p class="font-semibold">{{ currentTestimonial.name }}</p>
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
                "{{ currentTestimonial.content }}"
              </blockquote>
              
              <!-- Author info -->
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-2xl font-bold text-[#5F5F7E] mb-2">{{ currentTestimonial.name }}</h4>
                  <p class="text-[#80819A] font-medium">
                    {{ currentTestimonial.position }}, {{ currentTestimonial.company }}
                  </p>
                </div>
                
                <!-- Rating and reviews -->
                <div class="text-right">
                  <div class="flex items-center justify-end space-x-1 mb-2">
                    <span v-for="i in currentTestimonial.rating" :key="i" class="text-yellow-400 text-xl">★</span>
                  </div>
                  <p class="text-[#80819A] text-sm">{{ currentTestimonial.reviews }} reviews on Yelp</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 🔹 Nút chuyển slide -->
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
        
        <!-- 🔹 Dấu chấm điều hướng -->
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
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

// ==========================
// ✅ Dữ liệu đánh giá khách hàng
// ==========================
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
    position: 'Marketing Director',
    company: 'TechCorp',
    content:
      'Thank you so much for your help! This is exactly what I was looking for. It really saves me time and effort. LearnSphere is exactly what our business was missing.',
    rating: 5,
    reviews: 12,
    emoji: '👩‍💼',
  },
  {
    name: 'John Smith',
    position: 'CEO',
    company: 'StartupXYZ',
    content:
      'LearnSphere has transformed the way we train our team. The platform is easy to use, packed with useful features, and the support is outstanding. Highly recommended for anyone running online training.',
    rating: 5,
    reviews: 28,
    emoji: '👨‍💻',
  },
  {
    name: 'Sarah Johnson',
    position: 'HR Manager',
    company: 'GlobalCorp',
    content:
      'I have tried many LMS platforms, but LearnSphere is by far the best. The progress tracking and detailed reporting features help us manage employee training more effectively than ever.',
    rating: 5,
    reviews: 45,
    emoji: '👩‍🎓',
  },
])

const currentTestimonial = computed(() => testimonials.value[currentIndex.value])

const nextTestimonial = () => {
  currentIndex.value = (currentIndex.value + 1) % testimonials.value.length
}

const previousTestimonial = () => {
  currentIndex.value =
    currentIndex.value === 0 ? testimonials.value.length - 1 : currentIndex.value - 1
}
</script>
