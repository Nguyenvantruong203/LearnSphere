<template>
  <LayoutHomepage>
    <HeroSection />

    <BlogList />

    <RelatedBlog />

  </LayoutHomepage>
</template>

<script setup lang="ts">
import LayoutHomepage from '@/pages/customer/layout/layoutHomepage.vue';
import HeroSection from '@/components/customer/blog/HeroSection.vue';
import BlogList from '@/components/customer/blog/BlogList.vue';
import RelatedBlog from '@/components/customer/blog/RelatedBlog.vue';
</script>
