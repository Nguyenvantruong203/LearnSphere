<template>
  <div class="w-screen h-screen grid lg:grid-cols-2">
    <div class="relative hidden lg:block p-8">
      <img :src="loginImageUrl" class="rounded-am w-full h-full object-cover" />
    </div>

    <div class="flex items-center justify-center p-6">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import loginImageUrl from '@/assets/images/login-image.png'
</script>
