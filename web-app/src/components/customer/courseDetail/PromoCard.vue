<template>
  <div class="relative rounded-[20px] overflow-hidden">
    <img :src="image" :alt="title" class="w-full h-[481px] object-cover" />
    
    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-end p-8">
      <div class="bg-red-600 bg-opacity-60 rounded-[12px] w-fit px-4 py-2 mb-6">
        <span class="text-white font-bold text-[24px]">{{ discount }}</span>
      </div>
      
      <h3 class="text-white font-semibold text-[32px] leading-[48px] mb-4 text-center">{{ title }}</h3>
      <p class="text-white text-[24px] leading-[43.2px]">{{ description }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  image: string
  discount: string
  title: string
  description: string
}

defineProps<Props>()
</script>
