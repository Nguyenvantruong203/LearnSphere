<template>
  <section class="py-16 px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Left - Ratings Overview -->
        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
          <h2 class="text-3xl font-bold text-[#2F327D] mb-8">Đánh giá khóa học</h2>
          
          <div class="flex items-center mb-8">
            <div class="text-center mr-8">
              <div class="text-5xl font-bold text-teal-600 mb-2">{{ averageRating }}</div>
              <div class="flex items-center justify-center mb-2">
                <a-rate :value="averageRating" disabled allow-half class="text-yellow-400" />
              </div>
              <div class="text-[#696984] font-medium">{{ totalReviews }} đánh giá</div>
            </div>
          </div>

          <div class="space-y-4">
            <RatingBar 
              v-for="rating in ratingBreakdown"
              :key="rating.label"
              :label="rating.label"
              :percentage="rating.percentage"
            />
          </div>

          <!-- Rating action -->
          <div class="mt-8 pt-6 border-t border-gray-200">
            <button class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold py-3 rounded-3xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
              Viết đánh giá
            </button>
          </div>
        </div>

        <!-- Right - Reviews -->
        <div class="space-y-6">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-[#2F327D]">Nhận xét gần đây</h3>
            <button class="text-teal-600 font-semibold hover:text-teal-700 transition-colors duration-300">
              Xem tất cả →
            </button>
          </div>

          <div class="space-y-6">
            <ReviewCard 
              v-for="review in recentReviews"
              :key="review.id"
              :avatar="review.avatar"
              :name="review.name"
              :time="review.time"
              :content="review.content"
              :rating="review.rating"
            />
          </div>

          <!-- Load more reviews -->
          <div class="text-center pt-6">
            <button class="bg-white border border-gray-200 text-[#2F327D] font-semibold px-8 py-3 rounded-3xl hover:bg-gray-50 hover:border-teal-300 transition-all duration-300">
              Xem thêm đánh giá
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import RatingBar from './RatingBar.vue'
import ReviewCard from './ReviewCard.vue'

// Mock data - trong thực tế sẽ được truyền từ props hoặc API
const averageRating = ref(4.5)
const totalReviews = ref(245)

const ratingBreakdown = ref([
  { label: '5 sao', percentage: 65 },
  { label: '4 sao', percentage: 25 },
  { label: '3 sao', percentage: 8 },
  { label: '2 sao', percentage: 1 },
  { label: '1 sao', percentage: 1 }
])

const recentReviews = ref([
  {
    id: 1,
    avatar: 'https://static.codia.ai/image/2025-10-02/4dOSBEkxQr.png',
    name: 'Nguyễn Văn A',
    time: '2 ngày trước',
    content: 'Khóa học rất hay và bổ ích. Giảng viên giảng dạy dễ hiểu, nội dung được trình bày một cách logic và có hệ thống.',
    rating: 5
  },
  {
    id: 2,
    avatar: 'https://static.codia.ai/image/2025-10-02/zcBGEiDwsb.png',
    name: 'Trần Thị B',
    time: '1 tuần trước',
    content: 'Tôi đã học được rất nhiều kiến thức mới từ khóa học này. Các bài tập thực hành rất phù hợp với thực tế.',
    rating: 4
  },
  {
    id: 3,
    avatar: 'https://static.codia.ai/image/2025-10-02/6cHBpT0D17.png',
    name: 'Lê Văn C',
    time: '2 tuần trước',
    content: 'Khóa học tuyệt vời! Nội dung cập nhật, phương pháp giảng dạy hiện đại. Rất đáng để đầu tư.',
    rating: 5
  }
])
</script>
