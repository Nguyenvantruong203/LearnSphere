<template>
  <section class="py-16 px-8">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-12">
        <h2 class="text-[30px] font-medium text-black">Top Education offers and deals are listed here</h2>
        <a-button type="link" class="text-primary font-bold text-[20px] p-0">
          See all
        </a-button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <PromoCard 
          v-for="(card, index) in promoCards"
          :key="index"
          :image="card.image"
          :discount="card.discount"
          :title="card.title"
          :description="card.description"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import PromoCard from './PromoCard.vue'

const promoCards = [
  {
    image: 'https://static.codia.ai/image/2025-10-02/1cuLJFXU1y.png',
    discount: '50%',
    title: 'FOR INSTRUCTORS',
    description: "TOTC's school management software helps traditional and online schools manage scheduling,"
  },
  {
    image: 'https://static.codia.ai/image/2025-10-02/A4o4nPSNTC.png',
    discount: '50%',
    title: 'FOR INSTRUCTORS',
    description: "TOTC's school management software helps traditional and online schools manage scheduling,"
  },
  {
    image: 'https://static.codia.ai/image/2025-10-02/Gt7GfoWzKH.png',
    discount: '50%',
    title: 'FOR INSTRUCTORS',
    description: "TOTC's school management software helps traditional and online schools manage scheduling,"
  }
]
</script>
