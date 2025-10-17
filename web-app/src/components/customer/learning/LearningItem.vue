<template>
  <div
    class="relative flex items-center justify-between px-4 py-2 cursor-pointer transition-all duration-300 group overflow-hidden"
    :class="[
      isActive
        ? 'bg-gradient-to-r from-teal-600 to-blue-600 text-white shadow-lg'
        : bgColor || 'bg-white hover:bg-gradient-to-r hover:from-teal-50 hover:to-blue-50 hover:shadow-md',
      expandable ? 'rounded-t-xl' : 'rounded-xl'
    ]"
    @click="handleClick"
  >
    <div
      v-if="isActive"
      class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-full"
    ></div>

    <!-- 📘 Content -->
    <div class="flex items-center flex-1 min-w-0 gap-4 z-10">
      <!-- Icon -->
      <div
        class="flex items-center justify-center"
        :class="isActive ? 'text-white' : 'text-teal-600 group-hover:text-teal-700'"
      >
        <slot name="icon" />
      </div>

      <!-- Title -->
      <div class="flex-1 min-w-0">
        <h3
          class="font-semibold text-base transition-colors truncate"
          :class="isActive ? 'text-white' : 'text-gray-800 group-hover:text-gray-900'"
          :title="title"
        >
          {{ title }}
        </h3>
      </div>

      <!-- Extra (ví dụ: quiz, action button, icon...) -->
      <div
        class="flex items-center gap-2 transition-colors"
        :class="isActive ? 'text-white' : 'text-gray-500 group-hover:text-teal-600'"
      >
        <slot name="extra" />
      </div>
    </div>

    <!-- 🔽 Expand Arrow -->
    <div v-if="expandable" class="ml-3 z-10">
      <div
        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300"
        :class="isActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-gray-200'"
      >
        <svg
          class="w-4 h-4 transform transition-transform duration-300"
          :class="[
            expanded ? 'rotate-90' : 'rotate-0',
            isActive ? 'text-white' : 'text-gray-600'
          ]"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 5l7 7-7 7"
          />
        </svg>
      </div>
    </div>

    <!-- ✨ Subtle gradient overlay on hover (optional aesthetic) -->
    <div
      v-if="!isActive"
      class="absolute inset-0 bg-gradient-to-r from-teal-50/0 to-blue-50/0 group-hover:from-teal-50/60 group-hover:to-blue-50/60 transition-all duration-300"
    ></div>
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
.text-white {
  text-shadow: 0 0.5px 1px rgba(0, 0, 0, 0.25);
}
</style>
