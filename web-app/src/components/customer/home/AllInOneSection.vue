<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-6 text-center">
      <div v-motion
        class="mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Phần mềm đám mây tất cả trong một
        </h2>
        <p class="text-xl text-[#696984] max-w-4xl mx-auto leading-relaxed">
          LearnSphere là bộ phần mềm trực tuyến mạnh mẽ kết hợp tất cả các công cụ cần thiết để vận hành một trường học hoặc văn phòng thành công.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-10">
        <div v-for="(feature, index) in features" :key="feature.title"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.2, duration: 0.8, type: 'spring' } }"
          class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl text-center transition-all duration-500 hover:-translate-y-2 border border-gray-100">
          
          <div class="relative mb-8">
            <div :class="[feature.bgColor, 'w-24 h-24 rounded-2xl mx-auto flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg']">
              <i :class="feature.icon" class="text-3xl text-white"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <h3 class="text-2xl font-bold text-[#2F327D] mb-4 group-hover:text-teal-600 transition-colors">
            {{ feature.title }}
          </h3>
          <p class="text-[#696984] leading-relaxed">
            {{ feature.description }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const features = ref([
  {
    title: 'Thanh toán & Hóa đơn trực tuyến',
    description: 'Kiểm soát đơn giản và bảo mật các giao dịch tài chính và pháp lý của tổ chức bạn. Gửi hóa đơn và hợp đồng tùy chỉnh.',
    icon: 'fas fa-file-invoice-dollar',
    bgColor: 'bg-gradient-to-br from-blue-500 to-blue-600'
  },
  {
    title: 'Lập lịch & Theo dõi điểm danh dễ dàng',
    description: 'Lên lịch và đặt phòng học tại một hoặc nhiều cơ sở. Giữ hồ sơ chi tiết về việc điểm danh của học sinh.',
    icon: 'fas fa-calendar-check',
    bgColor: 'bg-gradient-to-br from-red-500 to-red-600'
  },
  {
    title: 'Theo dõi khách hàng',
    description: 'Tự động hóa và theo dõi email gửi đến cá nhân hoặc nhóm. Hệ thống tích hợp của LearnSphere giúp tổ chức của bạn có trật tự.',
    icon: 'fas fa-users-cog',
    bgColor: 'bg-gradient-to-br from-green-500 to-green-600'
  }
]);
</script>
