<template>
    <section class="relative bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-hidden min-h-[80vh]">
        <!-- Enhanced Background decoration -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-400/8 to-blue-400/8"></div>
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-cyan-300/20 to-transparent rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-teal-300/20 to-transparent rounded-full blur-3xl">
            </div>
            <div
                class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMiIgZmlsbD0icmdiYSgxNDgsMTYzLDE4NCwwLjEpIi8+Cjwvc3ZnPg==')] opacity-20">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Left Content - Takes 3 columns -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Breadcrumb / Category -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div
                            class="flex items-center space-x-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold text-sm px-4 py-2 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <BookOutlined class="text-base" />
                            <span>{{ course?.subject || 'Khóa học lập trình' }}</span>
                        </div>
                        <div
                            class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full px-4 py-2 shadow-sm">
                            <span class="text-sm text-gray-600">Cấp độ:</span>
                            <span class="text-sm font-semibold text-gray-800">Trung cấp</span>
                        </div>
                        <div
                            class="flex items-center space-x-2 bg-emerald-50 border border-emerald-200 rounded-full px-4 py-2">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-emerald-700">Đang mở</span>
                        </div>
                    </div>

                    <!-- Course Title -->
                    <div class="space-y-6">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-slate-800 leading-tight">
                            {{ course?.title || 'AWS Certified Solutions Architect' }}
                        </h1>

                        <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-4xl">
                            {{ course?.description }}
                        </p>
                    </div>

                    <!-- Enhanced Instructor Info -->
                    <div
                        class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-white/50 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img :src="course?.instructor?.avatar_url || 'https://static.codia.ai/image/2025-10-02/4dOSBEkxQr.png'"
                                    :alt="course?.instructor?.name || 'Instructor'"
                                    class="w-16 h-16 rounded-full object-cover shadow-lg ring-4 ring-white" />
                                <div
                                    class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-3 border-white shadow-sm">
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-xl font-bold text-slate-800">{{ course?.instructor?.name || 'Lina'
                                        }}</span>
                                    <div class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        Pro
                                    </div>
                                </div>
                                <p class="text-slate-600 text-sm mb-2">Senior AWS Solutions Architect</p>
                                <div class="flex items-center space-x-4 text-sm text-slate-500">
                                    <span>⭐ 4.9 (2,547 đánh giá)</span>
                                    <span>👥 12,345 học viên</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Course Stats -->
                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-4">
                        <div
                            class="bg-white/90 backdrop-blur-sm rounded-xl p-4 border border-white/50 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                            <div
                                class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-teal-600 transition-colors">
                                {{ course?.total_topics }}
                            </div>
                            <div class="text-sm text-slate-600">Chủ đề</div>
                        </div>
                        <div
                            class="bg-white/90 backdrop-blur-sm rounded-xl p-4 border border-white/50 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                            <div
                                class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-teal-600 transition-colors">
                                {{ course?.total_lessons }}
                            </div>
                            <div class="text-sm text-slate-600">Bài học</div>
                        </div>
                        <div
                            class="bg-white/90 backdrop-blur-sm rounded-xl p-4 border border-white/50 shadow-sm hover:shadow-md transition-all duration-300 text-center group">
                            <div
                                class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-teal-600 transition-colors">
                                4.9
                            </div>
                            <div class="text-sm text-slate-600">Đánh giá</div>
                        </div>
                    </div>

                    <!-- What You'll Learn Preview -->
                    <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl p-6 border border-teal-100">
                        <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                            <TrophyOutlined class="text-teal-600 mr-2" />
                            Bạn sẽ học được gì
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-start space-x-3">
                                <CheckOutlined class="text-teal-600 mt-1 flex-shrink-0" />
                                <span class="text-slate-700">Thiết kế kiến trúc AWS hiệu quả</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <CheckOutlined class="text-teal-600 mt-1 flex-shrink-0" />
                                <span class="text-slate-700">Quản lý bảo mật và tuân thủ</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <CheckOutlined class="text-teal-600 mt-1 flex-shrink-0" />
                                <span class="text-slate-700">Tối ưu hóa chi phí cloud</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <CheckOutlined class="text-teal-600 mt-1 flex-shrink-0" />
                                <span class="text-slate-700">Chuẩn bị thi chứng chỉ AWS</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Enhanced Course Card -->
                <div class="lg:col-span-2">
                    <div class="sticky top-8">
                        <div
                            class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden hover:shadow-3xl transition-all duration-500">
                            <!-- Course Image -->
                            <div class="relative overflow-hidden">
                                <img :src="course?.thumbnail_url || 'https://static.codia.ai/image/2025-10-02/7OkwkSfwnx.png'"
                                    :alt="course?.title || 'Course'"
                                    class="w-full h-56 object-cover transition-transform duration-700 hover:scale-110" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                <div
                                    class="absolute top-4 right-4 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                    50% OFF
                                </div>
                                <div
                                    class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white text-sm px-3 py-1 rounded-full">
                                    Preview khóa học
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Pricing -->
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-3 mb-2">
                                        <span class="text-4xl font-bold text-teal-600">{{ formatPrice(course?.price)
                                            }}</span>
                                        <div class="text-right">
                                            <span class="text-lg font-semibold text-gray-400 line-through">$99.99</span>
                                        </div>
                                    </div>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                        <p class="text-red-700 font-semibold text-sm flex items-center justify-center">
                                            ⏰ <span class="ml-1">Còn 11 giờ với giá ưu đãi!</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- CTA Buttons -->
                                <div class="space-y-3">
                                    <template v-if="hasAccess">
                                        <button
                                            class="w-full h-14 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-bold text-lg rounded-xl hover:from-emerald-600 hover:to-green-600 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                                            @click="goToMyCourses">
                                            Vào học ngay
                                        </button>
                                    </template>

                                    <template v-else>
                                        <button
                                            class="w-full h-14 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-bold text-lg rounded-xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                                            @click="buyNow">
                                            Mua ngay
                                        </button>
                                        <button
                                            class="w-full h-12 border-2 border-teal-500 text-teal-600 font-semibold rounded-xl hover:bg-teal-50 transition-all duration-300"
                                            @click="addToCart">
                                            Thêm vào giỏ hàng
                                        </button>
                                    </template>
                                </div>

                                <!-- Course Includes -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-bold text-slate-800">Khóa học bao gồm</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                                <CheckOutlined class="text-teal-600 text-sm" />
                                            </div>
                                            <span class="text-slate-700 font-medium">Truy cập trọn đời</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                                <MobileOutlined class="text-teal-600 text-sm" />
                                            </div>
                                            <span class="text-slate-700 font-medium">Học trên mọi thiết bị</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                                <TrophyOutlined class="text-teal-600 text-sm" />
                                            </div>
                                            <span class="text-slate-700 font-medium">Chứng chỉ hoàn thành</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                                <span class="text-teal-600 text-sm">💰</span>
                                            </div>
                                            <span class="text-slate-700 font-medium">Hoàn tiền 30 ngày</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Share Section -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-bold text-slate-800 mb-4">Chia sẻ khóa học</h3>
                                    <div class="flex space-x-2">
                                        <button
                                            class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-all duration-300 hover:scale-110 shadow-md">
                                            <i class="fab fa-facebook text-white text-sm"></i>
                                        </button>
                                        <button
                                            class="w-10 h-10 bg-sky-500 rounded-lg flex items-center justify-center hover:bg-sky-600 transition-all duration-300 hover:scale-110 shadow-md">
                                            <i class="fab fa-twitter text-white text-sm"></i>
                                        </button>
                                        <button
                                            class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition-all duration-300 hover:scale-110 shadow-md">
                                            <i class="fab fa-linkedin text-white text-sm"></i>
                                        </button>
                                        <button
                                            class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center hover:bg-green-600 transition-all duration-300 hover:scale-110 shadow-md">
                                            <i class="fab fa-whatsapp text-white text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { CheckOutlined, MobileOutlined, TrophyOutlined, BookOutlined } from '@ant-design/icons-vue'
import { notification } from 'ant-design-vue'
import type { Course } from '@/types/Course'
import { useRouter } from 'vue-router'
import { CartStorage } from '@/helpers/cartStorage'
import { ref, onMounted } from 'vue'
import { courseApi } from '@/api/customer/courseApi'

interface Props {
    course?: Course
}

const props = defineProps<Props>()
const router = useRouter()
const hasAccess = ref(false)

// --- helper lấy user ---
const getUserId = (): string | null => {
    const auth = JSON.parse(localStorage.getItem('client_auth') || '{}')
    return auth?.user?.id ? String(auth.user.id) : null
}

// --- Thêm vào giỏ hàng ---
const addToCart = () => {
    if (!props.course) return
    const userId = getUserId()
    if (!userId) {
        notification.warning({ message: 'Vui lòng đăng nhập để thêm vào giỏ hàng' })
        return
    }

    const currentCart = CartStorage.getCart(userId)
    const exists = currentCart.find((c: any) => c.id === props.course?.id)
    if (exists) {
        notification.warning({
            message: 'Khóa học đã có trong giỏ hàng',
            description: `${props.course.title} đã có trong giỏ hàng của bạn.`
        })
        return
    }

    CartStorage.addItem(userId, {
        id: props.course.id,
        title: props.course.title,
        price: props.course.price,
        thumbnail_url: props.course.thumbnail_url,
        quantity: 1,
    })

    notification.success({
        message: 'Đã thêm vào giỏ hàng',
        description: `${props.course.title} đã được thêm vào giỏ hàng của bạn.`
    })
}

// --- Mua ngay ---
const buyNow = () => {
    if (!props.course) return
    const userId = getUserId()
    if (!userId) {
        notification.warning({ message: 'Vui lòng đăng nhập để mua khóa học' })
        return
    }

    const currentCart = CartStorage.getCart(userId)
    const exists = currentCart.find((c: any) => c.id === props.course?.id)
    if (!exists) {
        CartStorage.addItem(userId, {
            id: props.course.id,
            title: props.course.title,
            price: props.course.price,
            thumbnail_url: props.course.thumbnail_url,
            quantity: 1,
        })
    }
    router.push('/cart')
}

// --- Vào học ---
const goToMyCourses = () => {
    if (props.course?.id) {
        router.push(`/learning/${props.course.id}`)
    }
}

// --- Format giá ---
const formatPrice = (price?: number) => {
    if (!price) return '₫1,299,000'
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price)
}

// ✅ Kiểm tra đã mua khóa học chưa
onMounted(async () => {
    const userId = getUserId()
    if (!userId || !props.course?.id) return
    try {
        const res = await courseApi.checkAccess(props.course.id)
        hasAccess.value = !!res?.hasAccess
    } catch (err) {
        console.error('checkAccess error', err)
    }
})
</script>
