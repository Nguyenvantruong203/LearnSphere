<template>
  <span
    v-if="priceNumber > 0"
    class="font-semibold"
    v-bind="$attrs"
  >
    {{ formattedPrice }}
  </span>
  <span
    v-else
    class="font-semibold text-green-600"
    v-bind="$attrs"
  >
    Miễn phí 🎉
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

defineOptions({ inheritAttrs: false })

const props = defineProps<{
  price: number | string | null
}>()

const priceNumber = computed(() => {
  if (!props.price) return 0
  const numeric = String(props.price).replace(/[^0-9.-]/g, '')
  return parseFloat(numeric) || 0
})

const formattedPrice = computed(() => {
  return priceNumber.value.toLocaleString('vi-VN', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })
})
</script>
