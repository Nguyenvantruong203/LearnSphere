<template>
  <section class="py-24 bg-gradient-to-br from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Sứ mệnh & Tầm nhìn
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto">
          Chúng tôi cam kết mang đến giải pháp giáo dục toàn diện và hiện đại nhất
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-20">
        <!-- Mission -->
        <div v-motion
          :initial="{ opacity: 0, x: -50 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.2, duration: 0.8 } }"
          class="group">
          <div class="relative p-8 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-3xl border border-gray-100 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
            <div class="absolute -top-6 -left-6 w-12 h-12 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-xl">
              <i class="fas fa-bullseye text-white text-xl"></i>
            </div>
            
            <h3 class="text-2xl font-bold text-[#2F327D] mb-4 mt-4">Sứ mệnh</h3>
            <p class="text-[#696984] leading-relaxed mb-6">
              Tạo ra một nền tảng học tập trực tuyến tiên tiến, giúp mọi người tiếp cận được 
              kiến thức chất lượng cao một cách dễ dàng và hiệu quả, không phân biệt địa lý hay hoàn cảnh.
            </p>
            
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                <span class="text-sm text-[#696984]">Giáo dục chất lượng cao cho mọi người</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                <span class="text-sm text-[#696984]">Công nghệ tiên tiến trong giáo dục</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                <span class="text-sm text-[#696984]">Trải nghiệm học tập cá nhân hóa</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Vision -->
        <div v-motion
          :initial="{ opacity: 0, x: 50 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.4, duration: 0.8 } }"
          class="group">
          <div class="relative p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl border border-gray-100 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
            <div class="absolute -top-6 -left-6 w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
              <i class="fas fa-eye text-white text-xl"></i>
            </div>
            
            <h3 class="text-2xl font-bold text-[#2F327D] mb-4 mt-4">Tầm nhìn</h3>
            <p class="text-[#696984] leading-relaxed mb-6">
              Trở thành nền tảng giáo dục trực tuyến hàng đầu tại Việt Nam và khu vực Đông Nam Á, 
              góp phần nâng cao trình độ và kỹ năng của hàng triệu người học.
            </p>
            
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                <span class="text-sm text-[#696984]">Nền tảng giáo dục hàng đầu khu vực</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                <span class="text-sm text-[#696984]">Hệ sinh thái học tập toàn diện</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                <span class="text-sm text-[#696984]">Cộng đồng học tập toàn cầu</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Values -->
      <div v-motion
        :initial="{ opacity: 0, y: 50 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }">
        <div class="text-center mb-12">
          <h3 class="text-3xl font-bold text-[#2F327D] mb-4">Giá trị cốt lõi</h3>
          <p class="text-[#696984] text-lg">Những nguyên tắc định hướng mọi hoạt động của chúng tôi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(value, index) in values" :key="value.id"
            v-motion
            :initial="{ opacity: 0, y: 30, scale: 0.9 }"
            :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: 0.8 + index * 0.1, duration: 0.6, type: 'spring' } }"
            class="text-center group">
            <div class="relative inline-block mb-6">
              <div :class="value.bgColor" class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                <i :class="value.icon + ' ' + value.iconColor + ' text-2xl'"></i>
              </div>
            </div>
            <h4 class="text-xl font-bold text-[#2F327D] mb-3">{{ value.title }}</h4>
            <p class="text-[#696984] text-sm leading-relaxed">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Value {
  id: number
  title: string
  description: string
  icon: string
  iconColor: string
  bgColor: string
}

const values = ref<Value[]>([
  {
    id: 1,
    title: 'Chất lượng',
    description: 'Cam kết mang đến nội dung học tập chất lượng cao nhất từ các chuyên gia hàng đầu',
    icon: 'fas fa-medal',
    iconColor: 'text-yellow-600',
    bgColor: 'bg-gradient-to-br from-yellow-100 to-orange-100'
  },
  {
    id: 2,
    title: 'Đổi mới',
    description: 'Không ngừng nghiên cứu và áp dụng công nghệ mới để cải thiện trải nghiệm học tập',
    icon: 'fas fa-lightbulb',
    iconColor: 'text-blue-600',
    bgColor: 'bg-gradient-to-br from-blue-100 to-cyan-100'
  },
  {
    id: 3,
    title: 'Tận tâm',
    description: 'Luôn đặt lợi ích và thành công của học viên lên hàng đầu trong mọi quyết định',
    icon: 'fas fa-heart',
    iconColor: 'text-red-600',
    bgColor: 'bg-gradient-to-br from-red-100 to-pink-100'
  },
  {
    id: 4,
    title: 'Minh bạch',
    description: 'Xây dựng niềm tin thông qua sự minh bạch trong thông tin và quy trình học tập',
    icon: 'fas fa-handshake',
    iconColor: 'text-green-600',
    bgColor: 'bg-gradient-to-br from-green-100 to-emerald-100'
  }
])
</script>
