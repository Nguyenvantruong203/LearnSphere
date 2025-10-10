<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <!-- Header -->
      <div
        v-motion
        class="text-center lg:text-left mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
      >
        <h2 class="text-[#2F327D] text-2xl lg:text-5xl font-bold mb-6">
          Choose Your Favorite Courses from Top Categories
        </h2>
        <p class="text-[#696984] text-lg max-w-3xl lg:mx-0 mx-auto">
          Explore diverse learning fields designed to help you develop your skills and knowledge
        </p>
      </div>

      <!-- Categories Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="(category, index) in categories"
          :key="category.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{
            opacity: 1,
            y: 0,
            scale: 1,
            transition: { delay: index * 0.1, duration: 0.8, type: 'spring' }
          }"
        >
          <CategoryCard :category="category" />
        </div>
      </div>

      <!-- View All Categories Button -->
      <div
        v-motion
        class="text-center mt-16"
        :initial="{ opacity: 0, y: 30 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
      >
        <button
          class="group px-10 py-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-full font-semibold text-lg hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl"
        >
          <span class="flex items-center space-x-2">
            <span>View All Categories</span>
            <i class="fas fa-grid-3x3 group-hover:rotate-12 transition-transform duration-300"></i>
          </span>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import CategoryCard from './CategoryCard.vue'

const categories = ref([
  {
    id: 1,
    title: 'Design',
    description:
      'Learn graphic design, UI/UX, and modern creative tools to craft beautiful and professional products.',
    icon: 'fas fa-palette',
    bgColor: 'bg-gradient-to-br from-pink-100 to-pink-200',
    iconColor: 'text-pink-600'
  },
  {
    id: 2,
    title: 'Programming',
    description:
      'Master programming languages and frameworks to build professional web and mobile applications.',
    icon: 'fas fa-code',
    bgColor: 'bg-gradient-to-br from-blue-100 to-blue-200',
    iconColor: 'text-blue-600'
  },
  {
    id: 3,
    title: 'Web Development',
    description:
      'From frontend to backend, learn how to build modern websites and web apps using the latest technologies.',
    icon: 'fas fa-laptop-code',
    bgColor: 'bg-gradient-to-br from-purple-100 to-purple-200',
    iconColor: 'text-purple-600'
  },
  {
    id: 4,
    title: 'Business',
    description:
      'Develop management, entrepreneurship, and business strategy skills to succeed in the corporate world.',
    icon: 'fas fa-briefcase',
    bgColor: 'bg-gradient-to-br from-emerald-100 to-emerald-200',
    iconColor: 'text-emerald-600'
  },
  {
    id: 5,
    title: 'Marketing',
    description:
      'Learn digital marketing, content marketing, and effective branding strategies in the digital age.',
    icon: 'fas fa-bullhorn',
    bgColor: 'bg-gradient-to-br from-orange-100 to-orange-200',
    iconColor: 'text-orange-600'
  },
  {
    id: 6,
    title: 'Photography',
    description:
      'Master photography techniques, editing skills, and build your own creative photography style.',
    icon: 'fas fa-camera',
    bgColor: 'bg-gradient-to-br from-red-100 to-red-200',
    iconColor: 'text-red-600'
  },
  {
    id: 7,
    title: 'Acting',
    description:
      'Develop acting, expression, and communication skills to confidently perform in front of audiences.',
    icon: 'fas fa-theater-masks',
    bgColor: 'bg-gradient-to-br from-yellow-100 to-yellow-200',
    iconColor: 'text-yellow-600'
  },
  {
    id: 8,
    title: 'Data Analysis',
    description:
      'Learn how to collect, process, and analyze data to make smart and effective business decisions.',
    icon: 'fas fa-chart-line',
    bgColor: 'bg-gradient-to-br from-indigo-100 to-indigo-200',
    iconColor: 'text-indigo-600'
  }
])
</script>
