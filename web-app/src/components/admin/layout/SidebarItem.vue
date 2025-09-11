<template>
  <div 
    class="flex items-center py-[10px] mb-2 rounded-2xl cursor-pointer transition-all duration-200"
    :class="active ? 'bg-white shadow-md' : 'hover:bg-gray-50'"
  >
    <div 
      class="w-8 h-8 rounded-xl flex items-center justify-center mr-3"
      :class="active ? 'bg-primary' : 'bg-white shadow-sm'"
    >
      <component :is="icon" class="text-sm" :class="active ? 'text-white' : 'text-primary'" />
    </div>
    <span 
      class="text-sm font-bold"
      :class="active ? 'text-gray-700' : 'text-gray-400'"
    >
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
