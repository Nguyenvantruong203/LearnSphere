<template>
  <a-modal
    :open="localOpen"
    @update:open="updateOpen"
    title="All Reviews"
    width="800px"
    :footer="false"
  >
    <div class="space-y-6">

      <!-- Review Items -->
      <ReviewCard
        v-for="review in reviews"
        :key="review.id"
        :avatar="review.user.avatar_url"
        :name="review.user.name"
        :created_at="review.created_at"
        :content="review.comment"
        :rating="review.rating"
      />

      <!-- Pagination -->
      <div class="flex justify-center mt-6">
        <a-pagination
          :total="pagination.total"
          :current="pagination.current_page"
          :pageSize="pagination.per_page"
          @change="loadPage"
          show-less-items
        />
      </div>

    </div>
  </a-modal>
</template>


<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import ReviewCard from './ReviewCard.vue'
import { reviewApi } from '@/api/customer/reviewApi'

const props = defineProps<{
  courseId: number | string
  open: boolean
}>()

const emit = defineEmits(['update:open'])

/**
 * 🔥 localOpen = computed có setter
 * Đây là cách đúng của Vue 3 khi muốn hỗ trợ v-model
 */
const localOpen = computed({
  get: () => props.open,
  set: (val) => emit('update:open', val)
})

const updateOpen = (val: boolean) => {
  localOpen.value = val
}

const reviews = ref<any[]>([])
const pagination = ref({
  current_page: 1,
  total: 0,
  per_page: 10
})

const loadPage = async (page = 1) => {
  const res = await reviewApi.getCourseReviews(props.courseId, page, 10)
  reviews.value = res.data.data
  pagination.value = res.data
}

watch(localOpen, (isOpen) => {
  if (isOpen) loadPage(1)
})
</script>
