<template>
    <div class="h-full w-full bg-white">
        <AppHeader />
        <slot />
        <AppFooter />
    </div>
</template>

<script setup lang="ts">
import AppHeader from '@/components/customer/layout/AppHeader.vue'
import AppFooter from '@/components/customer/layout/AppFooter.vue'
</script>
