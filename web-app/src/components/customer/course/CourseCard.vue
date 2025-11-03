<template>
  <router-link :to="showProgress
    ? { name: 'Learning', params: { courseId: course.id } }
    : { name: 'CourseDetail', params: { id: course.id } }"
    class="group block bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
    <!-- Course Image -->
    <div class="relative overflow-hidden">
      <img :src="course.thumbnail_url" :alt="course.title"
        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110" />
      <div
        class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
      </div>

      <div v-if="showProgress"
        class="absolute bottom-3 left-3 bg-white/95 backdrop-blur-md rounded-xl px-4 py-2 shadow-lg">
        <span class="text-gray-800 text-sm font-semibold">{{ course.progress }}</span>
      </div>
    </div>

    <!-- Course Content -->
    <div class="p-6 space-y-4">
      <h3
        class="text-[#2F327D] text-xl font-bold leading-tight group-hover:text-teal-600 transition-colors duration-300 line-clamp-2">
        {{ course.title }}
      </h3>

      <p v-if="course.description" class="text-[#696984] text-sm leading-relaxed line-clamp-2">
        {{ course.description }}
      </p>

      <div class="flex items-center space-x-3">
        <div class="relative">
          <img :src="course.instructor.avatar_url" :alt="course.instructor.name"
            class="w-10 h-10 rounded-full object-cover shadow-md" />
          <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
        </div>
        <div>
          <span class="text-gray-800 text-sm font-semibold block">{{ course.instructor.name }}</span>
          <span class="text-gray-500 text-xs">Giảng viên</span>
        </div>
      </div>

      <div v-if="showPrice" class="flex items-center justify-between pt-2">
        <div class="space-y-1">
          <FormatPrice :price="course.price" class="text-green-500" />
          <div class="flex items-center space-x-1 text-yellow-400">
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <i class="fas fa-star text-xs"></i>
            <span class="text-gray-500 text-xs ml-1">(4.9)</span>
          </div>
        </div>
        <button
          class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:from-teal-600 hover:to-cyan-600 transform hover:scale-105 shadow-lg">
          View Detail
        </button>
      </div>

      <div v-if="showProgress" class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Tiến độ học tập</span>
          <span class="text-teal-600 font-semibold">{{ course.progress }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div class="bg-gradient-to-r from-teal-500 to-cyan-500 h-2 rounded-full" :style="{ width: course.progress }">
          </div>
        </div>
        <button
          class="w-full mt-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-2 rounded-xl font-semibold hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105">
          Tiếp tục học
        </button>
      </div>
    </div>
  </router-link>
</template>


<script setup lang="ts">
import type { MappedCourse } from '@/types/Course'
import FormatPrice from '@/components/common/FormatPrice.vue'

defineProps<{
  course: MappedCourse
  showProgress?: boolean
  showPrice?: boolean
}>()
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
