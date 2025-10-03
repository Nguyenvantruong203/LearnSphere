<template>
  <div class="flex items-center space-x-4">
    <span class="text-lg text-[#2F327D] font-semibold w-16">{{ label }}</span>
    <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
      <div 
        class="bg-gradient-to-r from-teal-500 to-cyan-500 h-3 rounded-full transition-all duration-500 ease-out"
        :style="{ width: `${percentage}%` }"
      ></div>
    </div>
    <span class="text-sm text-[#696984] font-medium w-12 text-right">{{ percentage }}%</span>
  </div>
</template>

<script setup lang="ts">
interface Props {
  label: string
  percentage: number
}

defineProps<Props>()
</script>
