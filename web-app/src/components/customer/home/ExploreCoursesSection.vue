<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6">
      <div v-motion
        class="flex justify-between items-center mb-12"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D]">
          Khám phá khóa học
        </h2>
        <a href="#" class="text-[#49BBBD] font-semibold text-lg hover:text-[#2F327D] transition-colors group">
          <span>Xem tất cả</span>
          <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
      </div>
      
      <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <div v-for="(course, index) in courses" :key="course.title"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }">
          <CourseCard :course="course" />
        </div>
      </div>
      
      <!-- View More Button -->
      <div v-motion
        class="text-center mt-16"
        :initial="{ opacity: 0, y: 30 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }">
        <button class="group px-10 py-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-full font-semibold text-lg hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
          <span class="flex items-center space-x-2">
            <span>Xem thêm khóa học</span>
            <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
          </span>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import CourseCard from './CourseCard.vue'
import type { Course } from './CourseCard.vue'

const courses = ref<Course[]>([
  { 
    title: 'UI/UX Design', 
    lessons: 25, 
    author: 'Jane Doe', 
    price: '3.200.000đ',
    icon: 'fas fa-palette',
    bgColor: 'bg-gradient-to-br from-orange-400 to-orange-600'
  },
  { 
    title: 'Illustration', 
    lessons: 30, 
    author: 'John Smith', 
    price: '4.500.000đ',
    icon: 'fas fa-paint-brush',
    bgColor: 'bg-gradient-to-br from-red-400 to-pink-600'
  },
  { 
    title: 'Photography', 
    lessons: 22, 
    author: 'Emily White', 
    price: '2.800.000đ',
    icon: 'fas fa-camera',
    bgColor: 'bg-gradient-to-br from-purple-500 to-purple-700'
  },
  { 
    title: 'Web Development', 
    lessons: 40, 
    author: 'Chris Green', 
    price: '5.500.000đ',
    icon: 'fas fa-code',
    bgColor: 'bg-gradient-to-br from-blue-500 to-indigo-600'
  },
  {
    title: 'Digital Marketing',
    lessons: 18,
    author: 'Sarah Johnson',
    price: '3.800.000đ',
    icon: 'fas fa-bullhorn',
    bgColor: 'bg-gradient-to-br from-green-500 to-emerald-600'
  },
  {
    title: 'Data Science',
    lessons: 35,
    author: 'Michael Brown',
    price: '6.200.000đ',
    icon: 'fas fa-chart-line',
    bgColor: 'bg-gradient-to-br from-cyan-500 to-teal-600'
  },
  {
    title: 'Mobile App Design',
    lessons: 28,
    author: 'Lisa Davis',
    price: '4.200.000đ',
    icon: 'fas fa-mobile-alt',
    bgColor: 'bg-gradient-to-br from-yellow-400 to-orange-500'
  },
  {
    title: 'Content Writing',
    lessons: 20,
    author: 'Alex Wilson',
    price: '2.500.000đ',
    icon: 'fas fa-pen-fancy',
    bgColor: 'bg-gradient-to-br from-pink-500 to-rose-600'
  }
]);
</script>
