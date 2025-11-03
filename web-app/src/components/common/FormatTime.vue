<template>
  <span :class="computedClass" :title="fullTime">
    {{ displayTime }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import localizedFormat from 'dayjs/plugin/localizedFormat'
import 'dayjs/locale/vi'

// Kích hoạt plugin
dayjs.extend(relativeTime)
dayjs.extend(localizedFormat)
dayjs.locale('vi')

const props = defineProps<{
  time: string | Date | null
  format?: string        // custom format (ví dụ: 'HH:mm', 'DD/MM/YYYY')
  relative?: boolean     // true → "3 phút trước"
  short?: boolean        // true → chỉ hiển thị giờ hoặc ngày gọn
  class?: string         // custom class
}>()

const displayTime = computed(() => {
  if (!props.time) return '—'
  const t = dayjs(props.time)
  if (props.relative) return t.fromNow()
  if (props.format) return t.format(props.format)
  if (props.short) {
    return t.isSame(dayjs(), 'day') ? t.format('HH:mm') : t.format('DD/MM')
  }
  return t.format('DD/MM/YYYY HH:mm')
})

const fullTime = computed(() => {
  if (!props.time) return ''
  return dayjs(props.time).format('DD/MM/YYYY HH:mm:ss')
})

const computedClass = computed(() => props.class || 'text-xs text-gray-500')
</script>
