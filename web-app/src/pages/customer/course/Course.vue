<template>
  <LayoutHomepage>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12 relative z-10">
      <CourseSearchBar @search="handleSearch" />
    </div>

    <CourseList ref="courseList" :title="isSearching ? 'Kết quả tìm kiếm' : 'Tất cả khóa học'" :subtitle="isSearching && currentSearchPayload?.searchText ?
      searchResults.total + ' kết quả cho ' + currentSearchPayload.searchText : ''"
      :filters="isSearching ? currentSearchPayload?.filters : {}" emptyText="Không tìm thấy khóa học" />

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

onMounted(async () => {
  await loadAllCourses()
})

const loadAllCourses = async () => {
  try {
    loading.value = true
    isSearching.value = false
    currentPage.value = 1

    const response = await courseApi.getAllCourses({
      page: currentPage.value,
      per_page: searchResults.per_page
    })
    Object.assign(searchResults, response)
  } catch (error: any) {
    notification.error({
      message: 'Lỗi tải khóa học',
      description: error?.message || 'Không thể tải danh sách khóa học'
    })
  } finally {
    loading.value = false
  }
}

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

    const response = await courseApi.getAllCourses(params)
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
</script>
