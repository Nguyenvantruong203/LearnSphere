<template>
  <LayoutHomepage>
    <main>
      <!-- Hero Section -->
      <HeroSection />
      
      <!-- All-in-One Cloud Software Section -->
      <AllInOneSection />
      
      <!-- What is LearnSphere Section -->
      <WhatIsSection />
      
      <!-- Our Success Section -->
      <SuccessSection />
      
      <!-- Features Section -->
      <FeaturesSection />
      
      <!-- Explore Courses Section -->
      <ExploreCoursesSection />
      
      <!-- Testimonials Section -->
      <TestimonialsSection />
      
      <!-- News Section -->
      <NewsSection />
    </main>
  </LayoutHomepage>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import LayoutHomepage from './layout/layoutHomepage.vue';

import HeroSection from '@/components/customer/home/HeroSection.vue';
import AllInOneSection from '@/components/customer/home/AllInOneSection.vue';
import WhatIsSection from '@/components/customer/home/WhatIsSection.vue';
import SuccessSection from '@/components/customer/home/SuccessSection.vue';
import FeaturesSection from '@/components/customer/home/FeaturesSection.vue';
import ExploreCoursesSection from '@/components/customer/home/ExploreCoursesSection.vue';
import TestimonialsSection from '@/components/customer/home/TestimonialsSection.vue';
import NewsSection from '@/components/customer/home/NewsSection.vue';

const router = useRouter();
</script>

<style scoped>
.bg-gradient-blue-teal {
  background: linear-gradient(90deg, #136CB5 0%, #49BBBD 100%);
}
</style>
