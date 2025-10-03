<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="mb-12">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ title }}
                </h2>
                <p class="text-gray-600" v-if="subtitle">{{ subtitle }}</p>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="i in 8" :key="i" class="bg-white rounded-xl overflow-hidden shadow-sm">
                    <a-skeleton active :paragraph="{ rows: 4 }" />
                </div>
            </div>

            <!-- Results -->
            <div v-else-if="courses.data.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <CourseCard v-for="course in courses.data" :key="course.id" :course="mapCourse(course)" show-price />
            </div>


            <!-- Empty -->
            <div v-else class="text-center py-16">
                <div class="text-gray-400 text-6xl mb-4">📚</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Không có khóa học nào</h3>
                <p class="text-gray-600">
                    {{ emptyText }}
                </p>
            </div>

            <!-- Pagination -->
            <div v-if="courses.data.length > 0 && courses.last_page > 1" class="flex justify-center mt-8">
                <a-pagination v-model:current="currentPage" :total="courses.total" :page-size="courses.per_page"
                    :show-size-changer="false" show-quick-jumper @change="onPageChange" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import { notification } from 'ant-design-vue'
import { customerCourseApi } from '@/api/customer/courseApi'
import CourseCard from './CourseCard.vue'
import type { Course, PaginationCourse, MappedCourse } from '@/types/Course'

const props = defineProps<{
    title: string
    subtitle?: string
    filters?: Record<string, any>
    emptyText?: string
}>()

const courses = reactive<PaginationCourse<Course>>({
    current_page: 1,
    data: [],
    first_page_url: '',
    from: 0,
    last_page: 1,
    last_page_url: '',
    links: [],
    next_page_url: null,
    path: '',
    per_page: 4,
    prev_page_url: null,
    to: 0,
    total: 0
})

const loading = ref(false)
const currentPage = ref(1)

const fetchCourses = async () => {
    try {
        loading.value = true
        const response = await customerCourseApi.searchCourses({
            page: currentPage.value,
            per_page: courses.per_page,
            ...props.filters
        })
        Object.assign(courses, response)
    } catch (error: any) {
        notification.error({
            message: 'Lỗi tải khóa học',
            description: error?.message || 'Không thể tải danh sách khóa học'
        })
    } finally {
        loading.value = false
    }
}

const onPageChange = (page: number) => {
    currentPage.value = page
    fetchCourses()
}

watch(
    () => props.filters,
    () => {
        currentPage.value = 1
        fetchCourses()
    },
    { deep: true, immediate: true }
)

const mapCourse = (course: Course): MappedCourse => ({
    id: course.id,
    title: course.title,
    description: course.short_description || course.description,
    price: course.price > 0 ? `${course.price.toLocaleString()} ${course.currency}` : 'Miễn phí',
    originalPrice: course.price > 0 ? `${(course.price * 1.2).toLocaleString()} ${course.currency}` : undefined,
    image: course.thumbnail_url || '/placeholder-course.jpg',
    category: course.level,
    duration: '4 giờ',
    instructor: {
        name: course.instructor?.name || 'Giảng viên ẩn danh',
        avatar_url: course.instructor?.avatar_url || '/placeholder-avatar.jpg'
    }
})
</script>
