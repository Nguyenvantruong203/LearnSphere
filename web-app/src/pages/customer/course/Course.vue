<template>
  <LayoutHomepage>
    <div class="relative bg-gradient-to-br from-indigo-50 via-blue-50 to-violet-100 overflow-hidden">
      <!-- Background gradient -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute left-20 w-40 h-40 bg-gradient-to-r from-indigo-400 to-blue-400 rounded-full blur-3xl"></div>
        <div
          class="absolute bottom-20 right-20 w-32 h-32 bg-gradient-to-r from-violet-400 to-purple-400 rounded-full blur-3xl">
        </div>
        <div
          class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-sky-300 to-indigo-300 rounded-full blur-3xl opacity-5">
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12 relative z-10">
        <CourseSearchBar @search="handleSearch" />
      </div>

      <CourseList
        :title="isSearching ? 'Search Results' : 'All Courses'"
        :subtitle="isSearching && searchResults.total ? `${searchResults.total} results` : ''"
        :courses="searchResults.data"
        :total="searchResults.total"
        :loading="loading"
        emptyText="No courses found"
      />
    </div>

    <CategorySection />
    <CourseSection />
  </LayoutHomepage>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { notification } from 'ant-design-vue'
import LayoutHomepage from '../layout/layoutHomepage.vue'
import CategorySection from '@/components/customer/course/CategorySection.vue'
import CourseSection from '@/components/customer/course/CourseSection.vue'
import CourseSearchBar from '@/components/customer/search/CourseSearchBar.vue'
import CourseList from '@/components/customer/course/CourseList.vue'
import { courseApi } from '@/api/customer/courseApi'
import type { CourseSearchPayload, Course, PaginationCourse } from '@/types/Course'

const loading = ref(false)
const isSearching = ref(false)
const currentSearchPayload = ref<CourseSearchPayload | null>(null)

const searchResults = reactive<PaginationCourse<Course>>({
  current_page: 1,
  data: [],
  per_page: 12,
  total: 0,
  last_page: 1,
  from: 0,
  to: 0,
  path: '',
  links: [],
  next_page_url: null,
  prev_page_url: null,
  first_page_url: null,
  last_page_url: null,
})

onMounted(loadAllCourses)

async function loadAllCourses() {
  try {
    loading.value = true
    isSearching.value = false
    const response = await courseApi.getAllCourses({
      page: 1,
      per_page: searchResults.per_page,
    })
    Object.assign(searchResults, response)
  } catch (e: any) {
    notification.error({
      message: 'Failed to load courses',
      description: e?.message || 'Unable to fetch the course list',
    })
  } finally {
    loading.value = false
  }
}

async function handleSearch(payload: CourseSearchPayload) {
  try {
    loading.value = true
    isSearching.value = true
    currentSearchPayload.value = payload

    const response = await courseApi.getAllCourses({
      page: 1,
      per_page: searchResults.per_page,
      search: payload.search || undefined,
      ...payload.filters,
    })
    Object.assign(searchResults, response)
  } catch (e: any) {
    notification.error({
      message: 'Search error',
      description: e?.message || 'Unable to perform search',
    })
  } finally {
    loading.value = false
  }
}
</script>
