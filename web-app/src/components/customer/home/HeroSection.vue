<template>
    <section class="relative overflow-hidden min-h-screen bg-client-ellipse">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>

        <div class="container mx-auto px-6 lg:px-12 flex flex-col lg:flex-row items-center relative z-10 min-h-screen">
            <!-- Left text content -->
            <div v-motion class="lg:w-1/2 text-white space-y-8 mb-12 lg:mb-0" :initial="{ opacity: 0, x: -80 }"
                :enter="{ opacity: 1, x: 0, transition: { duration: 0.8, ease: 'easeOut' } }">

                <!-- Badge -->
                <div v-motion
                    class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium text-white/90 border border-white/20"
                    :initial="{ opacity: 0, y: -20 }"
                    :enter="{ opacity: 1, y: 0, transition: { delay: 0.2, duration: 0.6 } }">
                    🎓 Nền tảng học trực tuyến hàng đầu
                </div>

                <!-- Main heading -->
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight tracking-tight">
                    <span class="text-orange-400 drop-shadow-lg">Học tập</span> trực tuyến<br />
                    <span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
                        giờ đây dễ dàng hơn
                    </span>
                </h1>

                <!-- Description -->
                <p class="text-xl lg:text-2xl text-white/90 max-w-2xl leading-relaxed font-light">
                    LearnSphere là nền tảng học tập thú vị sẽ dạy bạn theo cách tương tác và hiệu quả hơn bao giờ hết
                </p>

                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pt-4">
                    <a-button type="primary" shape="round" size="large"
                        class="!h-14 !px-8 !text-lg font-semibold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 bg-gradient-to-r from-orange-400 to-orange-500 border-0">
                        Tham gia miễn phí
                    </a-button>

                    <div class="flex items-center space-x-3 cursor-pointer group" @click="playDemo">
                        <button
                            class="w-14 h-14 flex items-center justify-center rounded-full border-2 border-white/50 text-white hover:bg-white hover:text-teal-500 transition-all duration-300 backdrop-blur-sm bg-white/10 group-hover:scale-110 group-hover:shadow-lg">
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </button>
                        <span class="text-lg font-medium text-white/90 group-hover:text-white transition-colors">
                            Xem cách hoạt động
                        </span>
                    </div>
                </div>

                <!-- Stats -->
                <div v-motion class="flex items-center space-x-8 pt-8" :initial="{ opacity: 0, y: 20 }"
                    :enter="{ opacity: 1, y: 0, transition: { delay: 0.4, duration: 0.6 } }">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">50K+</div>
                        <div class="text-sm text-white/70">Học viên</div>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">1,200+</div>
                        <div class="text-sm text-white/70">Khóa học</div>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">95%</div>
                        <div class="text-sm text-white/70">Hài lòng</div>
                    </div>
                </div>
            </div>

            <!-- Right image section -->
            <div v-motion class="relative lg:w-1/2 flex justify-center items-center"
                :initial="{ opacity: 0, scale: 0.8 }"
                :enter="{ opacity: 1, scale: 1, transition: { duration: 1.2, ease: 'easeOut' } }">

                <!-- Main image with enhanced styling -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-t from-teal-600/20 to-transparent rounded-3xl"></div>
                    <img src="@/assets/images/teacher.png" alt="Giáo viên"
                        class="max-h-[600px] lg:max-h-[700px] relative z-10 drop-shadow-2xl" />
                </div>

                <!-- Enhanced floating cards -->

                <!-- Students count card -->
                <div v-motion
                    class="absolute top-8 left-4 lg:left-12 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-4 border border-white/20 hover:shadow-3xl transition-all duration-300"
                    :initial="{ opacity: 0, y: -40, rotate: -5 }"
                    :enter="{ opacity: 1, y: 0, rotate: 0, transition: { delay: 0.6, duration: 0.8, type: 'spring' } }">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg">
                        📊
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-800">250K+</p>
                        <p class="text-sm text-gray-600 font-medium">Học viên đã hỗ trợ</p>
                    </div>
                </div>

                <!-- Achievement card -->
                <div v-motion
                    class="absolute top-16 right-2 lg:right-8 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-3 border border-white/20 hover:shadow-3xl transition-all duration-300 max-w-xs"
                    :initial="{ opacity: 0, x: 60, rotate: 5 }"
                    :enter="{ opacity: 1, x: 0, rotate: 0, transition: { delay: 0.9, duration: 0.8, type: 'spring' } }">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg">
                        🎉
                    </div>
                    <div>
                        <p class="text-base font-bold text-gray-800">Chúc mừng!</p>
                        <p class="text-sm text-gray-600 font-medium">Hoàn thành đăng ký</p>
                    </div>
                </div>

                <!-- Live class card -->
                <div v-motion
                    class="absolute bottom-8 left-0 lg:left-4 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-4 border border-white/20 hover:shadow-3xl transition-all duration-300 max-w-sm"
                    :initial="{ opacity: 0, y: 40, rotate: -3 }"
                    :enter="{ opacity: 1, y: 0, rotate: 0, transition: { delay: 1.2, duration: 0.8, type: 'spring' } }">
                    <img src="https://i.pravatar.cc/48" class="w-12 h-12 rounded-xl shadow-md" alt="Avatar giáo viên" />
                    <div class="flex-1">
                        <p class="text-base font-bold text-gray-800">Lớp UX Design</p>
                        <p class="text-sm text-gray-600 font-medium">Hôm nay lúc 12:00</p>
                        <a-button type="primary" size="small"
                            class="mt-2 !h-8 !px-4 !text-xs font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            Tham gia ngay
                        </a-button>
                    </div>
                </div>

                <!-- Rating card -->
                <div v-motion
                    class="absolute bottom-32 right-4 lg:right-12 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-3 border border-white/20 hover:shadow-3xl transition-all duration-300"
                    :initial="{ opacity: 0, scale: 0.8, rotate: 8 }"
                    :enter="{ opacity: 1, scale: 1, rotate: 0, transition: { delay: 1.5, duration: 0.8, type: 'spring' } }">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center text-white text-xl shadow-lg">
                        ⭐
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-800">4.9/5</p>
                        <p class="text-sm text-gray-600 font-medium">Đánh giá xuất sắc</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { notification } from 'ant-design-vue'

// Method để phát demo video
const playDemo = () => {
    notification.info({
        message: 'Demo Video',
        description: 'Tính năng xem demo sẽ được triển khai sớm!',
        placement: 'topRight'
    })
}
</script>
