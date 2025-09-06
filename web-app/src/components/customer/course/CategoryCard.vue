<template>
  <div class="bg-white rounded-2xl shadow-card p-8 hover:shadow-lg transition-shadow cursor-pointer group">
    <!-- Icon -->
    <div :class="[category.bgColor, 'w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform']">
      <i :class="[category.icon, category.iconColor, 'text-2xl']"></i>
    </div>
    
    <!-- Content -->
    <div class="space-y-4">
      <h3 class="text-secondary font-poppins text-2xl font-semibold">
        {{ category.title }}
      </h3>
      <p class="text-gray-medium font-poppins text-sm leading-relaxed">
        {{ category.description }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoryCard',
  props: {
    category: {
      type: Object,
      required: true
    }
  }
}
</script>
