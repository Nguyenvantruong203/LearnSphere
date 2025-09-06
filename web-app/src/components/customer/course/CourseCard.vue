<template>
  <div class="bg-white rounded-2xl shadow-card p-6 hover:shadow-lg transition-shadow">
    <!-- Course Image -->
    <div class="relative mb-4">
      <img 
        :src="course.image" 
        :alt="course.title" 
        class="w-full h-48 object-cover rounded-xl"
      />
      <div v-if="showProgress" class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1">
        <span class="text-gray-dark font-poppins text-sm font-medium">{{ course.progress }}</span>
      </div>
    </div>
    
    <!-- Course Content -->
    <div class="space-y-3">
      <!-- Category and Duration -->
      <div v-if="showPrice" class="flex items-center justify-between text-sm">
        <span class="text-primary font-poppins font-medium">{{ course.category }}</span>
        <span class="text-gray-medium font-poppins">{{ course.duration }}</span>
      </div>
      
      <!-- Title -->
      <h3 class="text-secondary font-poppins text-xl font-semibold leading-tight">
        {{ course.title }}
      </h3>
      
      <!-- Description -->
      <p v-if="course.description" class="text-gray-medium font-poppins text-sm leading-relaxed">
        {{ course.description }}
      </p>
      
      <!-- Instructor -->
      <div class="flex items-center space-x-3">
        <img 
          :src="course.avatar" 
          :alt="course.instructor" 
          class="w-8 h-8 rounded-full object-cover"
        />
        <span class="text-gray-dark font-poppins text-sm font-medium">{{ course.instructor }}</span>
      </div>
      
      <!-- Price -->
      <div v-if="showPrice" class="flex items-center space-x-3">
        <span class="text-primary font-poppins text-xl font-bold">{{ course.price }}</span>
        <span class="text-gray-light font-poppins text-sm line-through">{{ course.originalPrice }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseCard',
  props: {
    course: {
      type: Object,
      required: true
    },
    showProgress: {
      type: Boolean,
      default: false
    },
    showPrice: {
      type: Boolean,
      default: false
    }
  }
}
</script>
