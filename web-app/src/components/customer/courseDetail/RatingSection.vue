<template>
  <section class="py-16 px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Left - Ratings Overview -->
        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
          <h2 class="text-3xl font-bold text-[#2F327D] mb-8">Course Ratings</h2>

          <div class="flex items-center mb-8">
            <div class="text-center mr-8">
              <div class="text-5xl font-bold text-teal-600 mb-2">{{ averageRating }}</div>
              <div class="flex items-center justify-center mb-2">
                <a-rate :value="averageRating" disabled allow-half class="text-yellow-400" />
              </div>
              <div class="text-[#696984] font-medium">{{ totalReviews }} reviews</div>
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
            <button
              class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold py-3 rounded-3xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              Write a Review
            </button>
          </div>
        </div>

        <!-- Right - Reviews -->
        <div class="space-y-6">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-[#2F327D]">Recent Reviews</h3>
            <button
              class="text-teal-600 font-semibold hover:text-teal-700 transition-colors duration-300"
            >
              View All →
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
            <button
              class="bg-white border border-gray-200 text-[#2F327D] font-semibold px-8 py-3 rounded-3xl hover:bg-gray-50 hover:border-teal-300 transition-all duration-300"
            >
              Load More Reviews
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

// Mock data - in real use case, this would come from props or API
const averageRating = ref(4.5)
const totalReviews = ref(245)

const ratingBreakdown = ref([
  { label: '5 stars', percentage: 65 },
  { label: '4 stars', percentage: 25 },
  { label: '3 stars', percentage: 8 },
  { label: '2 stars', percentage: 1 },
  { label: '1 star', percentage: 1 }
])

const recentReviews = ref([
  {
    id: 1,
    avatar: 'https://static.codia.ai/image/2025-10-02/4dOSBEkxQr.png',
    name: 'John Nguyen',
    time: '2 days ago',
    content:
      'The course was very informative and well-structured. The instructor explained everything clearly and logically.',
    rating: 5
  },
  {
    id: 2,
    avatar: 'https://static.codia.ai/image/2025-10-02/zcBGEiDwsb.png',
    name: 'Emma Tran',
    time: '1 week ago',
    content:
      'I learned a lot of new skills from this course. The practical exercises are relevant and easy to follow.',
    rating: 4
  },
  {
    id: 3,
    avatar: 'https://static.codia.ai/image/2025-10-02/6cHBpT0D17.png',
    name: 'David Le',
    time: '2 weeks ago',
    content:
      'An excellent course! Up-to-date content and engaging teaching style. Definitely worth the investment.',
    rating: 5
  }
])
</script>
