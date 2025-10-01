<template>
  <div class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 cursor-pointer transform hover:-translate-y-2 border border-gray-100 relative overflow-hidden">
    <!-- Background gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-transparent to-gray-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    
    <!-- Icon -->
    <div class="relative z-10 mb-8">
      <div :class="[category.bgColor, 'w-20 h-20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl']">
        <i :class="[category.icon, category.iconColor, 'text-3xl group-hover:rotate-6 transition-transform duration-300']"></i>
      </div>
      <!-- Decorative dot -->
      <div class="absolute -top-2 -right-2 w-4 h-4 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 space-y-4">
      <h3 class="text-[#2F327D] text-2xl font-bold group-hover:text-teal-600 transition-colors duration-300">
        {{ category.title }}
      </h3>
      <p class="text-[#696984] text-sm leading-relaxed">
        {{ category.description }}
      </p>
      
      <!-- Action indicator -->
      <div class="flex items-center text-teal-600 font-semibold text-sm opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
        <span>Khám phá ngay</span>
        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Category {
  id: number
  title: string
  description: string
  icon: string
  bgColor: string
  iconColor: string
}

defineProps<{
  category: Category
}>()
</script>
