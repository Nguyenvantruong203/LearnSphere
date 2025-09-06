<template>
  <LayoutHomepage>
        <!-- Hero Section -->
        <HeroSection />

        <!-- Categories Section -->
        <CategorySection />

        <!-- Course Sections -->
        <CourseSection />
  </LayoutHomepage>
</template>

<script setup lang="ts">
import LayoutHomepage from './layout/layoutHomepage.vue';
import HeroSection from '@/components/customer/course/HeroSection.vue';
import CategorySection from '@/components/customer/course/CategorySection.vue';
import CourseSection from '@/components/customer/course/CourseSection.vue';
</script>
