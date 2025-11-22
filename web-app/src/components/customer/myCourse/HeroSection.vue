<template>
  <section class="relative bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 py-20 px-6 lg:px-20 overflow-hidden">
    <!-- Background decorative blobs -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
      <div class="absolute top-10 left-10 w-32 h-32 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full blur-3xl"></div>
      <div class="absolute bottom-10 right-10 w-40 h-40 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-orange-300 to-pink-300 rounded-full blur-3xl opacity-5"></div>
    </div>

    <div class="relative max-w-7xl mx-auto">
      <!-- Welcome Message -->
      <div v-motion
        class="mb-16 text-center lg:text-left"
        :initial="{ opacity: 0, y: 50 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 800, ease: 'easeOut' } }"
      >
        <h1 class="text-4xl lg:text-5xl font-black text-[#2F327D] leading-tight">
          Welcome back!<br />
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-500">
            Ready to continue learning?
          </span>
        </h1>
        <p class="mt-6 text-xl text-[#696984] max-w-3xl">
          Pick up right where you left off with your enrolled courses — updated, high-quality, and designed for your success.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-32">
        <div class="inline-block">
          <div class="w-16 h-16 border-4 border-teal-200 border-t-teal-500 rounded-full animate-spin mb-6"></div>
          <p class="text-lg text-gray-600 font-medium">Loading your courses...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="courses.length === 0" class="text-center py-32">
        <div class="mb-8">
          <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
       1</div>
        <p class="text-xl text-gray-600 mb-8">You haven't enrolled in any courses yet.</p>
        <a-button type="primary" size="large" class="px-10 py-6 text-lg font-semibold" @click="$router.push('/courses')">
          Explore Courses
        </a-button>
      </div>

      <!-- Course Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-16">
        <div
          v-for="(course, index) in courses"
          :key="course.id"
          v-motion
          :initial="{ opacity: 0, y: 60, scale: 0.95 }"
          :enter="{ 
            opacity: 1, 
            y: 0, 
            scale: 1, 
            transition: { 
              delay: index * 100, 
              duration: 600, 
              ease: 'easeOut',
              type: 'spring',
              stiffness: 80
            } 
          }"
        >
          <CourseCard :course="course" :show-progress="true" />
        </div>
      </div>

      <!-- Bottom Navigation & Stats -->
      <div v-if="courses.length > 0" class="flex flex-col lg:flex-row items-center justify-between gap-10">
        <!-- Pagination Arrows -->
        <div class="flex items-center gap-4">
          <button
            class="group w-16 h-16 bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg flex items-center justify-center hover:shadow-xl hover:bg-white transition-all duration-300 border border-gray-100"
            aria-label="Previous page"
          >
            <svg class="w-6 h-6 text-[#2F327D] group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button
            class="group w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl shadow-lg flex items-center justify-center hover:shadow-2xl transform hover:scale-110 transition-all duration-300"
            aria-label="Next page"
          >
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <!-- Stats -->
        <div class="text-center lg:text-right">
          <div class="text-4xl font-black text-[#2F327D]">{{ courses.length }}</div>
          <div class="text-lg text-[#696984] font-medium">Total Enrolled Courses</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { notification } from 'ant-design-vue'
import CourseCard from '@/components/customer/course/CourseCard.vue'
import { courseApi } from '@/api/customer/courseApi'

const courses = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await courseApi.getMyCourses()
    courses.value = res.data?.courses || res || []
  } catch (error: any) {
    notification.error({
      message: 'Failed to load courses',
      description: error?.message || 'Please try again later.'
    })
  } finally {
    loading.value = false
  }
})
</script>