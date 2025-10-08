<template>
  <div
    class="flex items-center justify-between px-4 py-2 cursor-pointer transition-all duration-300 group overflow-hidden relative"
    :class="[
      isActive
        ? 'bg-gradient-to-r from-teal-500 to-blue-500 text-white shadow-lg'
        : bgColor || 'bg-transparent hover:shadow-md',
      expandable ? 'rounded-t-xl' : 'rounded-xl'
    ]" @click="handleClick">

    <div v-if="!isActive"
      class="absolute inset-0 bg-gradient-to-r from-teal-50/0 to-blue-50/0 transition-all duration-300 opacity-0"></div>

    <div class="flex items-center flex-1 min-w-0 gap-4 relative z-10">
      <slot name="icon" />

      <div class="flex-1 min-w-0">
        <h3 class="font-semibold text-base transition-colors line-clamp-1"
          :class="isActive ? 'text-white' : 'text-gray-800 group-hover:text-gray-900'" :title="title">
          {{ title }}
        </h3>
      </div>

      <slot name="extra" />
    </div>

    <div v-if="expandable" class="ml-3 relative z-10">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300"
        :class="isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-gray-200'">
        <svg class="w-4 h-4 transform transition-transform duration-300" :class="[
          expanded ? 'rotate-90' : 'rotate-0',
          isActive ? 'text-white' : 'text-gray-600'
        ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>

    <div v-if="isActive" class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-full"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  isActive?: boolean
  expandable?: boolean
  expanded?: boolean
  bgColor?: string
}>()
const emit = defineEmits(['click'])

const handleClick = (e: MouseEvent) => {
  const target = e.target as HTMLElement

  if (target.closest('[data-no-bubble]')) return
  emit('click')
}
</script>

<style scoped>
.bg-client {
  background-color: #45b9b0;
}
</style>
