<template>
  <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
    <div class="flex items-start space-x-4">
      <div class="relative flex-shrink-0">
        <img :src="avatar" :alt="name" class="w-14 h-14 rounded-full object-cover shadow-md" />
        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
      </div>

      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h4 class="text-lg font-bold text-[#2F327D]">{{ name }}</h4>
            <div class="flex items-center space-x-3">
              <a-rate :value="rating" disabled class="text-yellow-400 text-sm" />
              <span class="text-sm text-[#696984] font-medium">{{ rating }}/5</span>
            </div>
          </div>
          <div class="flex items-center space-x-2 text-[#696984]">
            <ClockCircleOutlined class="text-gray-400" />
            <span class="text-sm font-medium">{{ formattedDate }}</span>
          </div>
        </div>

        <p class="text-[#696984] leading-relaxed">{{ content }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { ClockCircleOutlined } from '@ant-design/icons-vue'
import { formatReviewDate } from '@/utils/formatDate'

interface Props {
  avatar: string
  name: string
  created_at: string
  content: string
  rating?: number
}

const props = withDefaults(defineProps<Props>(), {
  rating: 5
})

const formattedDate = computed(() => formatReviewDate(props.created_at))
</script>

