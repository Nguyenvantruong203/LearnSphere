<template>
  <section class="py-24 bg-white">
    <div class="container mx-auto px-6 text-center">
      <div v-motion
        class="mb-20"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Tính năng của chúng tôi
        </h2>
        <p class="text-xl text-[#696984] max-w-4xl mx-auto leading-relaxed">
          Những tính năng đặc biệt này có thể làm cho các hoạt động học tập trở nên hiệu quả hơn
        </p>
      </div>

      <div class="space-y-32">
        <!-- Feature 1 - UI Design -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row-reverse items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                <span class="text-[#F48C06]">Giao diện người dùng</span> được thiết kế cho lớp học
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                Giáo viên có thể dễ dàng xem tất cả học sinh và dữ liệu lớp học cùng một lúc với giao diện trực quan và thân thiện.
              </p>
              
              <!-- Feature highlights -->
              <div class="space-y-4 mt-8">
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Dashboard tổng quan toàn diện</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Theo dõi tiến độ học tập realtime</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Giao diện responsive trên mọi thiết bị</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <!-- Main feature image -->
              <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                          <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Bảng điều khiển</span>
                      </div>
                      <span class="text-sm text-gray-500">Hôm nay</span>
                    </div>
                    <div class="h-32 bg-gradient-to-r from-orange-200 to-yellow-200 rounded-xl flex items-center justify-center">
                      <i class="fas fa-users text-orange-600 text-4xl"></i>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Floating elements -->
              <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-star text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Feature 2 - Tools -->
        <div v-motion
          :initial="{ opacity: 0, x: -60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                <span class="text-[#2F327D]">Công cụ</span> cho giáo viên và học sinh
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                Hệ thống có một bộ công cụ giảng dạy động được xây dựng để triển khai và sử dụng trong lớp học. Giáo viên có thể giao bài tập theo thời gian thực cho học sinh hoàn thành và nộp.
              </p>
              
              <!-- Feature highlights -->
              <div class="space-y-4 mt-8">
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Giao bài tập trực tuyến</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Chia sẻ tài liệu một cách dễ dàng</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Hệ thống thông báo thông minh</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <!-- Main feature image -->
              <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-gray-800">Công cụ giảng dạy</span>
                      <i class="fas fa-tools text-blue-600"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                      </div>
                      <div class="h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-video text-blue-600 text-xl"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Floating elements -->
              <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-cog text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Feature 3 - Assessments -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row-reverse items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                Đánh giá, <span class="text-[#23BDEE]">Bài kiểm tra,</span> Thi cử
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                Dễ dàng tạo các bài tập, bài kiểm tra và thi cử trực tuyến. Kết quả của học sinh được tự động nhập vào sổ điểm trực tuyến.
              </p>
              
              <!-- Feature highlights -->
              <div class="space-y-4 mt-8">
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Tạo quiz tương tác đa dạng</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Chấm điểm tự động và nhanh chóng</span>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="w-6 h-6 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                  </div>
                  <span class="text-[#696984]">Phân tích kết quả chi tiết</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <!-- Main feature image -->
              <div class="bg-gradient-to-br from-cyan-100 to-blue-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-gray-800">Quiz & Tests</span>
                      <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-question text-white text-sm"></i>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="h-4 bg-cyan-100 rounded-full"></div>
                      <div class="h-4 bg-cyan-100 rounded-full w-3/4"></div>
                      <div class="h-4 bg-cyan-100 rounded-full w-1/2"></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Floating elements -->
              <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-line text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// Component không cần logic phức tạp
</script>
