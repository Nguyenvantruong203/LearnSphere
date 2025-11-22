<template>
  <section class="py-16 px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <!-- Left: Summary -->
        <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
          <h2 class="text-3xl font-bold text-[#2F327D] mb-8 text-center">Course Ratings</h2>

          <div class="flex items-center justify-center mb-8">
            <div class="text-center mr-8">
              <div class="text-5xl font-bold text-teal-600 mb-2">{{ summary.average_rating }}</div>

              <div class="flex items-center justify-center mb-2">
                <a-rate :value="summary.average_rating" disabled allow-half class="text-yellow-400" />
              </div>

              <div class="text-[#696984] font-medium">
                {{ summary.total_reviews }} reviews
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <RatingBar v-for="item in breakdownList" :key="item.label" :label="item.label"
              :percentage="item.percentage" />
          </div>

          <!-- Write Review -->
          <div class="mt-8 pt-6 border-t border-gray-200" v-if="canReview">
            <button @click="showWriteReview = true"
              class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold py-3 rounded-3xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
              Write a Review
            </button>
          </div>
          <div v-if="!canReview && reviewReason" class="mt-4 text-sm text-gray-500">
            <span v-if="reviewReason === 'not_enrolled'">
              You need to enroll in this course before you can write a review.
            </span>
            <span v-if="reviewReason === 'already_reviewed'">
              You have already reviewed this course.
            </span>
          </div>
        </div>

        <!-- Right: Recent Reviews -->
        <div class="space-y-6">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-[#2F327D]">Recent Reviews</h3>
            <a href="#" @click.prevent="openAllReviews = true"
              class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
              <span>View All</span>
              <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
          </div>

          <div class="space-y-6">
            <ReviewCard v-for="review in reviews" :key="review.id" :avatar="review.user.avatar_url"
              :name="review.user.name" :created_at="review.created_at" :content="review.comment"
              :rating="review.rating" />
          </div>
        </div>
      </div>
    </div>

    <!-- Write Review Modal -->
    <a-modal v-model:open="showWriteReview" title="Write a Review" @ok="submitReview">
      <div class="mb-4">
        <label class="block mb-2 font-semibold">Rating</label>
        <a-rate v-model:value="newReview.rating" allow-half />
      </div>

      <div>
        <label class="block mb-2 font-semibold">Comment</label>
        <a-textarea v-model:value="newReview.comment" placeholder="Write your review..." :rows="4" />
      </div>
    </a-modal>
    <AllReviewsModal :courseId="props.courseId" v-model:open="openAllReviews" />
  </section>
</template>


<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import RatingBar from './RatingBar.vue'
import ReviewCard from './ReviewCard.vue'
import { reviewApi } from '@/api/customer/reviewApi'
import { message } from 'ant-design-vue'
import AllReviewsModal from './AllReviewsModal.vue'

const props = defineProps<{ courseId: string | number }>()

const summary = ref({
  total_reviews: 0,
  average_rating: 0,
  stars: { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }
})

const reviews = ref<any[]>([])
const showWriteReview = ref(false)
const canReview = ref(false)
const reviewReason = ref('')
const openAllReviews = ref(false)
const newReview = ref({
  rating: 0,
  comment: ''
})

const checkCanReview = async () => {
  try {
    const res = await reviewApi.canReview(props.courseId)
    canReview.value = res.can_review
    reviewReason.value = res.reason
  } catch (e) {
    canReview.value = false
  }
}


/**
 * Fetch Summary
 */
const fetchSummary = async () => {
  try {
    const res = await reviewApi.getReviewSummary(props.courseId)
    summary.value = res.data
  } catch (e) {
    console.error(e)
  }
}

/**
 * Fetch Course Reviews
 */
const fetchReviews = async () => {
  const res = await reviewApi.getCourseReviews(props.courseId, 1, 3)
  reviews.value = res.data.data
}

/** 
 * Submit review
 */
const submitReview = async () => {
  try {
    await reviewApi.createReview(props.courseId, newReview.value)
    message.success('Review submitted!')

    showWriteReview.value = false
    newReview.value = { rating: 0, comment: '' }

    fetchSummary()
    fetchReviews()

  } catch (e: any) {
    message.error(e?.response?.data?.message || 'Failed to submit review')
  }
}

/**
 * Convert breakdown summary (stars) → percentage bar
 */
const breakdownList = computed(() => {
  const total = summary.value.total_reviews || 1
  return [
    { label: '5 stars', percentage: Math.round((summary.value.stars[5] / total) * 100) },
    { label: '4 stars', percentage: Math.round((summary.value.stars[4] / total) * 100) },
    { label: '3 stars', percentage: Math.round((summary.value.stars[3] / total) * 100) },
    { label: '2 stars', percentage: Math.round((summary.value.stars[2] / total) * 100) },
    { label: '1 star', percentage: Math.round((summary.value.stars[1] / total) * 100) }
  ]
})

onMounted(() => {
  fetchSummary()
  fetchReviews()
  checkCanReview()
})
</script>
