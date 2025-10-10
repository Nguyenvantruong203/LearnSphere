<template>
  <span :class="weightClass">
    {{ formattedWeight }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  weight?: string | number | null
  defaultWeight?: number
}>()

const formattedWeight = computed(() => {
  const rawValue = props.weight ?? props.defaultWeight ?? 1
  const value = Number(rawValue)
  if (isNaN(value)) return '—'
  const rounded = Number(value.toFixed(2))
  if (Number.isInteger(rounded)) return rounded.toString()
  return rounded.toString()
})

const weightClass = computed(() => {
  const value = Number(props.weight ?? props.defaultWeight ?? 1)
  if (value >= 5) return 'text-red-500 font-semibold'
  if (value >= 3) return 'text-orange-500 font-medium'
  return 'text-gray-700'
})
</script>
