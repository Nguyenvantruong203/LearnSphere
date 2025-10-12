<template>
  <div class="relative w-screen h-screen">
    <img :src="loginImageUrl" alt="Login Background"
      class="absolute inset-0 w-full h-full object-cover object-bottom" />

    <div class="absolute inset-0 flex items-center justify-center">
      <div class="bg-white/95 rounded-xl p-8 shadow-lg w-full max-w-md">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import loginImageUrl from '@/assets/images/login-image.png'
</script>
