<template>
  <LayoutHomepage>
    <!-- Hero Section -->
    <HeroSection />

    <!-- Mission & Vision Section -->
    <MissionSection />

    <!-- Team Section -->
    <TeamSection />
  </LayoutHomepage>
</template>

<script setup lang="ts">
import LayoutHomepage from './layout/layoutHomepage.vue'
import HeroSection from '@/components/customer/about/HeroSection.vue'
import MissionSection from '@/components/customer/about/MissionSection.vue'
import TeamSection from '@/components/customer/about/TeamSection.vue'
</script>
