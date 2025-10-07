<template>
    <LayoutHomepage>
        <div v-if="loading" class="flex items-center justify-center min-h-screen">
            <a-spin size="large" />
        </div>
        <div v-else-if="error" class="flex items-center justify-center min-h-screen">
            <a-result
                status="error"
                title="Lỗi tải dữ liệu"
                :sub-title="error"
            >
                <template #extra>
                    <a-button type="primary" @click="fetchCourse">
                        Thử lại
                    </a-button>
                </template>
            </a-result>
        </div>
        <div v-else-if="course">
            <HeroSection :course="course" />
            <RatingSection />
            <MarketingArticles />
            <FeatureSection />
            <PromotionalCards />
        </div>
    </LayoutHomepage>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { notification } from 'ant-design-vue'
import LayoutHomepage from '@/pages/customer/layout/layoutHomepage.vue'
import HeroSection from '@/components/customer/courseDetail/HeroSection.vue'
import RatingSection from '@/components/customer/courseDetail/RatingSection.vue'
import MarketingArticles from '@/components/customer/courseDetail/MarketingArticles.vue'
import FeatureSection from '@/components/customer/courseDetail/FeatureSection.vue'
import PromotionalCards from '@/components/customer/courseDetail/PromotionalCards.vue'
import { courseApi } from '@/api/customer/courseApi'
import type { Course } from '@/types/Course'

const route = useRoute()
const loading = ref(false)
const error = ref<string | null>(null)
const course = ref<Course | null>(null)

const fetchCourse = async () => {
    const courseId = route.params.id as string
    if (!courseId) {
        error.value = 'Course ID không hợp lệ'
        return
    }

    loading.value = true
    error.value = null

    try {
        course.value = await courseApi.getCourse(courseId)
    } catch (err: any) {
        error.value = err?.message || 'Không thể tải thông tin khóa học'
        notification.error({
            message: 'Lỗi',
            description: error.value
        })
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchCourse()
})
</script>
