<template>
  <div class="flex items-center py-[10px] mb-2 rounded-2xl cursor-pointer transition-all duration-200"
    :class="active ? 'bg-primary/10 shadow-md' : 'hover:bg-gray-50'">
    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-all duration-200"
      :class="active ? 'bg-primary' : 'bg-white shadow-sm'">
      <component :is="icon" class="text-sm" :class="active ? 'text-primary' : 'text-gray-400'" />

    </div>

    <span class="text-sm font-bold transition-colors duration-200" :class="active ? 'text-gray-800' : 'text-gray-500'">
      {{ label }}
    </span>
  </div>
</template>

<script setup lang="ts">
interface Props {
  icon: any
  label: string
  active?: boolean
}

defineProps<Props>()
</script>
