<template>
  <LayoutHomepage>
    <main class="max-w-7xl mx-auto px-4 py-8">
        <OrderSummary />
    </main>
  </LayoutHomepage>
</template>

<script setup lang="ts">
import LayoutHomepage from '../layout/layoutHomepage.vue'
import OrderSummary from '@/components/customer/cart/OrderSummary.vue'
</script>
