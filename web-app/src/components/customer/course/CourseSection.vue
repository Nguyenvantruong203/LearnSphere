<template>
  <div>
    <!-- Recommended Courses -->
    <section class="relative py-24 px-6 lg:px-20 bg-gradient-to-br from-blue-50 to-indigo-50">
      <div class="relative max-w-7xl mx-auto">
        <!-- Header -->
        <div
          v-motion
          class="flex justify-between items-center mb-16"
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
        >
          <div>
            <h2 class="text-[#2F327D] text-4xl lg:text-5xl font-bold mb-2">
              Recommended Courses
            </h2>
            <p class="text-[#696984] text-lg">
              Based on your interests and learning history
            </p>
          </div>
          <a
            href="#"
            class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors"
          >
            <span>View All</span>
            <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"
            ></i>
          </a>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          <div
            v-for="(course, index) in recommendedCourses"
            :key="course.id"
            v-motion
            :initial="{ opacity: 0, y: 50, scale: 0.9 }"
            :enter="{
              opacity: 1,
              y: 0,
              scale: 1,
              transition: { delay: index * 0.1, duration: 0.8, type: 'spring' },
            }"
          >
            <CourseCard :course="course" :showPrice="true" />
          </div>
        </div>

        <!-- Navigation Arrows -->
        <div
          v-motion
          class="flex justify-end space-x-4"
          :initial="{ opacity: 0, x: 40 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.6, duration: 0.8 } }"
        >
          <button
            class="group w-14 h-14 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg flex items-center justify-center hover:bg-white hover:shadow-xl transition-all duration-300 border border-gray-100"
          >
            <i
              class="fas fa-chevron-left text-xl text-[#2F327D] group-hover:text-teal-500 group-hover:-translate-x-0.5 transition-all duration-300"
            ></i>
          </button>
          <button
            class="group w-14 h-14 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl shadow-lg flex items-center justify-center hover:shadow-xl transition-all duration-300 transform hover:scale-105"
          >
            <i
              class="fas fa-chevron-right text-xl text-white group-hover:translate-x-0.5 transition-transform duration-300"
            ></i>
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import CourseCard from '@/components/customer/course/CourseCard.vue'

const recommendedCourses = ref([
  {
    id: 1,
    title: 'AWS Certified Solutions Architect',
    description:
      'Learn how to design and deploy scalable systems on AWS following best practices and modern patterns.',
    instructor: 'Linh Nguyen',
    price: '2,000,000₫',
    image:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9eRRfPvnBF.png',
    avatar:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/vKrXEuwtJc.png',
    category: 'Cloud Computing',
    duration: '3 months',
  },
  {
    id: 2,
    title: 'Full Stack Web Development',
    description:
      'A complete course on web development from frontend to backend using React and Node.js.',
    instructor: 'Nam Tran',
    price: '2,400,000₫',
    image:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9kmyN1T7LL.png',
    avatar:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/07QdZx2H2X.png',
    category: 'Programming',
    duration: '4 months',
  },
  {
    id: 3,
    title: 'UI/UX Design Mastery',
    description:
      'Master the principles of interface and user experience design using Figma and Adobe XD.',
    instructor: 'Huong Le',
    price: '1,800,000₫',
    image:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fo0qk6bVR6.png',
    avatar:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/wOgB0ddBey.png',
    category: 'Design',
    duration: '2.5 months',
  },
  {
    id: 4,
    title: 'Digital Marketing Strategy',
    description:
      'Learn how to build effective digital marketing strategies and optimize ROI for your campaigns.',
    instructor: 'Tuan Pham',
    price: '1,500,000₫',
    image:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/K3DP4zHm6n.png',
    avatar:
      'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/pWGv4wy3i7.png',
    category: 'Marketing',
    duration: '2 months',
  },
])
</script>
