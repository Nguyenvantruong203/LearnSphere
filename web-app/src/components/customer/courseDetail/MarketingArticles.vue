<template>
  <section class="py-16 px-8">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-12">
        <h2 class="text-[30px] font-medium text-black">Marketing Articles</h2>
        <a-button type="link" class="text-primary font-bold text-[20px] p-0">
          See all
        </a-button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <ArticleCard 
          v-for="(article, index) in articles"
          :key="index"
          :image="article.image"
          :category="article.category"
          :title="article.title"
          :description="article.description"
          :author="article.author"
          :avatar="article.avatar"
          :originalPrice="article.originalPrice"
          :currentPrice="article.currentPrice"
          :duration="article.duration"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import ArticleCard from './ArticleCard.vue'

const articles = [
  {
    image: 'https://static.codia.ai/image/2025-10-02/402vXOrJZd.png',
    category: 'Design',
    title: 'AWS Certified solutions Architect',
    description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
    author: 'Lina',
    avatar: 'https://static.codia.ai/image/2025-10-02/4dOSBEkxQr.png',
    originalPrice: '$100',
    currentPrice: '$80',
    duration: '3 Month'
  },
  {
    image: 'https://static.codia.ai/image/2025-10-02/PiwPmSzQYH.png',
    category: 'Design',
    title: 'AWS Certified solutions Architect',
    description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
    author: 'Lina',
    avatar: 'https://static.codia.ai/image/2025-10-02/zcBGEiDwsb.png',
    originalPrice: '$100',
    currentPrice: '$80',
    duration: '3 Month'
  },
  {
    image: 'https://static.codia.ai/image/2025-10-02/e1WFyqAOcP.png',
    category: 'Design',
    title: 'AWS Certified solutions Architect',
    description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
    author: 'Lina',
    avatar: 'https://static.codia.ai/image/2025-10-02/6cHBpT0D17.png',
    originalPrice: '$100',
    currentPrice: '$80',
    duration: '3 Month'
  },
  {
    image: 'https://static.codia.ai/image/2025-10-02/MJSYfNc4Ag.png',
    category: 'Design',
    title: 'AWS Certified solutions Architect',
    description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
    author: 'Lina',
    avatar: 'https://static.codia.ai/image/2025-10-02/EohUQ9wsVL.png',
    originalPrice: '$100',
    currentPrice: '$80',
    duration: '3 Month'
  }
]
</script>
