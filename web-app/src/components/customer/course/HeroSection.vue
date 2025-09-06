<template>
  <section class="relative bg-gradient-to-r from-blue-100/20 to-blue-200/20 py-12 px-6 lg:px-20">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-20">
      <img 
        src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/Y2S5k0UgLE.svg" 
        alt="Background Pattern" 
        class="w-full h-full object-cover"
      />
    </div>
    
    <div class="relative max-w-7xl mx-auto">
      <!-- Welcome Text -->
      <div class="mb-8">
        <h1 class="text-secondary font-poppins text-4xl lg:text-5xl font-semibold mb-4">
          Welcome back, ready for your next lesson?
        </h1>
        <div class="flex justify-end">
          <a href="#" class="text-primary font-poppins text-xl font-bold hover:underline">
            View history
          </a>
        </div>
      </div>
      
      <!-- Course Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <CourseCard 
          v-for="course in heroCards" 
          :key="course.id"
          :course="course"
          :showProgress="true"
        />
      </div>
      
      <!-- Navigation Arrows -->
      <div class="flex justify-end space-x-4">
        <button class="w-12 h-12 bg-primary/50 rounded flex items-center justify-center hover:bg-primary/70 transition-colors">
          <i class="fas fa-chevron-left text-white text-lg"></i>
        </button>
        <button class="w-12 h-12 bg-primary rounded flex items-center justify-center hover:bg-primary/80 transition-colors">
          <i class="fas fa-chevron-right text-white text-lg"></i>
        </button>
      </div>
    </div>
  </section>
</template>

<script>
import CourseCard from './CourseCard.vue'

export default {
  name: 'HeroSection',
  components: {
    CourseCard
  },
  data() {
    return {
      heroCards: [
        {
          id: 1,
          title: 'AWS Certified Solutions Architect',
          instructor: 'Lina',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2gUZCDMuxr.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/49mzAi8e8L.png',
          progress: 'Lesson 5 of 7'
        },
        {
          id: 2,
          title: 'AWS Certified Solutions Architect',
          instructor: 'Lina',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/LL5sXfZDoD.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/XJp7XjhP09.png',
          progress: 'Lesson 5 of 7'
        },
        {
          id: 3,
          title: 'AWS Certified Solutions Architect',
          instructor: 'Lina',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/oGbsOPdjq8.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/DrGW9CRWYY.png',
          progress: 'Lesson 5 of 7'
        }
      ]
    }
  }
}
</script>
