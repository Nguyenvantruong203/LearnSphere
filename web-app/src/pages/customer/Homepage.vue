<template>
  <LayoutHomepage>
    <main>
      <HeroSection />
      
      <AllInOneSection />

      <WhatIsSection />
      
      <SuccessSection />
      
      <FeaturesSection />
      
      <TestimonialsSection />
      
      <NewsSection />

      <ChatBotWidget threadType="user_support" />

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
import TestimonialsSection from '@/components/customer/home/TestimonialsSection.vue';
import NewsSection from '@/components/customer/home/NewsSection.vue';
import ChatBotWidget from '@/components/common/chat/ChatBotWidget.vue'

const router = useRouter();
</script>
