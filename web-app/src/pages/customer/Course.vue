<template>
  <LayoutHomepage>
    <!-- Hero Section -->
    <HeroSection />

    <!-- Search Bar Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-12 relative z-10">
      <CourseSearchBar @search="handleSearch" />
    </div>

    <!-- Search Results Section -->
    <div v-if="isSearching" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Kết quả tìm kiếm</h2>
        <p class="text-gray-600">
          {{ searchResults.total }} khóa học được tìm thấy
          <span v-if="currentSearchPayload?.searchText">
            cho "{{ currentSearchPayload.searchText }}"
          </span>
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="bg-white rounded-xl overflow-hidden shadow-sm">
          <a-skeleton active :paragraph="{ rows: 4 }" />
        </div>
      </div>

      <!-- Search Results Grid -->
      <div v-else-if="searchResults.data.length > 0"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <CourseCard v-for="course in searchResults.data" :key="course.id" :course="{
          id: course.id,
          title: course.title,
          description: course.short_description || course.description,
          instructor: course.creator?.name || 'Giảng viên',
          price: course.price > 0 ? `${course.price.toLocaleString()} ${course.currency}` : 'Miễn phí',
          originalPrice: course.price > 0 ? `${(course.price * 1.2).toLocaleString()} ${course.currency}` : undefined,
          image: course.thumbnail_url || '/placeholder-course.jpg',
          avatar: course.creator?.avatar_url || '/placeholder-avatar.jpg',
          category: course.level,
          duration: '4 giờ'
        }" show-price />
      </div>

      <!-- No Results -->
      <div v-else class="text-center py-16">
        <div class="text-gray-400 text-6xl mb-4">🔍</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy khóa học</h3>
        <p class="text-gray-600">Hãy thử thay đổi từ khóa hoặc bộ lọc tìm kiếm</p>
      </div>

      <!-- Pagination -->
      <div v-if="searchResults.data.length > 0 && searchResults.last_page > 1" class="flex justify-center mt-8">
        <a-pagination v-model:current="currentPage" :total="searchResults.total" :page-size="searchResults.per_page"
          :show-size-changer="false" show-quick-jumper @change="handlePageChange" />
      </div>
    </div>

    
    <!-- Categories Section -->
    <CategorySection />

    <!-- Course Sections -->
    <CourseSection />
  </LayoutHomepage>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { notification } from 'ant-design-vue'
import LayoutHomepage from './layout/layoutHomepage.vue'
import HeroSection from '@/components/customer/course/HeroSection.vue'
import CategorySection from '@/components/customer/course/CategorySection.vue'
import CourseSection from '@/components/customer/course/CourseSection.vue'
import CourseSearchBar from '@/components/customer/search/CourseSearchBar.vue'
import CourseCard from '@/components/customer/course/CourseCard.vue'
import { customerCourseApi } from '@/api/customer/courseApi'
import type { CourseSearchPayload, CourseSearchParams } from '@/types/Course'
import type { Course, PaginationCourse } from '@/types/Course'

const loading = ref(false)
const isSearching = ref(false)
const currentPage = ref(1)
const currentSearchPayload = ref<CourseSearchPayload | null>(null)

const searchResults = reactive<PaginationCourse<Course>>({
  current_page: 1,
  data: [],
  first_page_url: '',
  from: 0,
  last_page: 1,
  last_page_url: '',
  links: [],
  next_page_url: null,
  path: '',
  per_page: 12,
  prev_page_url: null,
  to: 0,
  total: 0
})

const handleSearch = async (payload: CourseSearchPayload) => {
  try {
    loading.value = true
    isSearching.value = true
    currentPage.value = 1
    currentSearchPayload.value = payload

    const params: CourseSearchParams = {
      search: payload.searchText || undefined,
      subject: payload.filters?.subject,
      level: payload.filters?.level,
      language: payload.filters?.language,
      is_paid: payload.filters?.availability === 'paid'
        ? true
        : payload.filters?.availability === 'free'
          ? false
          : undefined,
      page: currentPage.value,
      per_page: searchResults.per_page
    }

    Object.keys(params).forEach(key => {
      if (params[key as keyof CourseSearchParams] === undefined) {
        delete params[key as keyof CourseSearchParams]
      }
    })

    const response = await customerCourseApi.searchCourses(params)
    Object.assign(searchResults, response)

  } catch (error: any) {
    notification.error({
      message: 'Lỗi tìm kiếm',
      description: error?.message || 'Không thể tìm kiếm khóa học'
    })
  } finally {
    loading.value = false
  }
}

const handlePageChange = async (page: number) => {
  if (!currentSearchPayload.value) return

  currentPage.value = page

  const params: CourseSearchParams = {
    search: currentSearchPayload.value?.searchText || undefined,
    subject: currentSearchPayload.value?.filters?.subject,
    level: currentSearchPayload.value?.filters?.level,
    language: currentSearchPayload.value?.filters?.language,
    is_paid: currentSearchPayload.value?.filters?.availability === 'paid'
      ? true
      : currentSearchPayload.value?.filters?.availability === 'free'
        ? false
        : undefined,
    page: currentPage.value,
    per_page: searchResults.per_page
  }

  try {
    loading.value = true
    const response = await customerCourseApi.searchCourses(params)
    Object.assign(searchResults, response)
  } catch (error: any) {
    notification.error({
      message: 'Lỗi tải trang',
      description: error?.message || 'Không thể tải trang tiếp theo'
    })
  } finally {
    loading.value = false
  }
}

</script>
