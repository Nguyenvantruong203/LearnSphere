<template>
  <div class="group rounded-2xl p-6 border hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center"
    :class="bgClass">
    <div
      class="relative w-10 h-10 mx-auto mb-3 rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300"
      :class="iconClass">
      <component :is="iconComponent"
        class="text-white text-[18px] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
    </div>
    <p class="text-sm text-gray-500 font-medium mb-1">{{ title }}</p>

    <p :class="valueClass">{{ value }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {
  CheckOutlined,
  CloseOutlined,
  CalendarOutlined,
  StarFilled,
} from '@ant-design/icons-vue'

const props = defineProps<{
  title: string
  color: 'blue' | 'green' | 'red' | 'purple'
  value: string | number
  icon: 'check' | 'x' | 'calendar' | 'star'
}>()

// 🎨 Bản đồ màu
const colorMap = {
  blue: {
    bg: 'from-blue-50 to-indigo-100',
    border: 'border-blue-200/50',
    icon: 'from-blue-500 to-indigo-600',
    text: 'text-blue-600',
  },
  green: {
    bg: 'from-green-50 to-emerald-100',
    border: 'border-green-200/50',
    icon: 'from-green-500 to-emerald-600',
    text: 'text-green-600',
  },
  red: {
    bg: 'from-red-50 to-rose-100',
    border: 'border-red-200/50',
    icon: 'from-red-500 to-rose-600',
    text: 'text-red-500',
  },
  purple: {
    bg: 'from-purple-50 to-pink-100',
    border: 'border-purple-200/50',
    icon: 'from-purple-500 to-pink-600',
    text: 'text-purple-600',
  },
}

const bgClass = computed(
  () =>
    `bg-gradient-to-br ${colorMap[props.color].bg} border ${colorMap[props.color].border}`
)
const iconClass = computed(
  () => `bg-gradient-to-br ${colorMap[props.color].icon}`
)
const valueClass = computed(
  () => `text-2xl font-bold ${colorMap[props.color].text}`
)

// 🔹 Dùng icon Ant Design
const iconComponent = computed(() => {
  switch (props.icon) {
    case 'check':
      return CheckOutlined
    case 'x':
      return CloseOutlined
    case 'calendar':
      return CalendarOutlined
    default:
      return StarFilled
  }
})
</script>

<style scoped>
.group {
  cursor: default;
}
</style>
