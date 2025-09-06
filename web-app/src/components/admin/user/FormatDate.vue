<template>
  <div>
    <div class="font-bold text-sm text-gray-700 capitalize">{{ formattedDate }}</div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
interface Props {
    date: string
}

const props = defineProps<Props>()

const formattedDate = computed(() => {
  if (!props.date) return ''
  
  try {
    const dateObj = new Date(props.date)
    
    // Kiểm tra xem date có hợp lệ không
    if (isNaN(dateObj.getTime())) return props.date
    
    const day = dateObj.getDate().toString().padStart(2, '0')
    const month = (dateObj.getMonth() + 1).toString().padStart(2, '0')
    const year = dateObj.getFullYear()
    
    return `${day}/${month}/${year}`
  } catch (error) {
    return props.date
  }
})
</script>