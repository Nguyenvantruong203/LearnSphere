<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="mb-12">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h2>
        <p class="text-gray-600" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="bg-white rounded-xl overflow-hidden shadow-sm">
          <a-skeleton active :paragraph="{ rows: 4 }" />
        </div>
      </div>

      <!-- Course list -->
      <div
        v-else-if="courses && courses.length > 0"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        <CourseCard v-for="course in courses" :key="course.id" :course="mapCourse(course)" show-price />
      </div>

      <!-- Empty state -->
      <div v-else class="text-center py-16">
        <div class="text-gray-400 text-6xl mb-4">📚</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No courses available</h3>
        <p class="text-gray-600">{{ emptyText }}</p>
      </div>

      <!-- Pagination -->
      <div v-if="showPagination" class="flex justify-center mt-8">
        <a-pagination
          v-model:current="currentPage"
          :total="total"
          :page-size="pageSize"
          :show-size-changer="false"
          show-quick-jumper
          @change="onPageChange"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import CourseCard from '@/components/customer/course/CourseCard.vue'
import type { Course, MappedCourse } from '@/types/Course'

const props = defineProps<{
  title: string
  subtitle?: string
  courses?: Course[]
  total?: number
  loading?: boolean
  emptyText?: string
}>()

// ✅ Pagination (optional for later use)
const currentPage = ref(1)
const pageSize = 12
const showPagination = computed(() => (props.total || 0) > pageSize)

function onPageChange(page: number) {
  currentPage.value = page
}

const mapCourse = (course: Course): MappedCourse => ({
  id: course.id,
  title: course.title,
  description: course.short_description || course.description,
  price: course.price > 0 ? `${course.price.toLocaleString()} ${course.currency}` : 'Free',
  thumbnail_url: course.thumbnail_url || '/placeholder-course.jpg',
  category: course.level,
  duration: '4 hours',
  instructor: {
    name: course.instructor?.name || 'Anonymous Instructor',
    avatar_url: course.instructor?.avatar_url || '/placeholder-avatar.jpg'
  }
})
</script>
