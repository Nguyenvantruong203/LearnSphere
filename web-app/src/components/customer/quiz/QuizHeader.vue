<template>
  <header class="bg-white/95 backdrop-blur-md border-b border-gray-200/60 shadow-lg sticky top-0 z-50">
    <div class="px-6 md:px-8 py-5">
      <div class="flex items-center justify-between">
        <!-- Left: Icon + Title với animation -->
        <div class="flex items-center gap-4 group">
          <div class="relative w-14 h-14 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
              {{ title }}
            </h1>
            <p class="text-sm md:text-base text-gray-500 font-medium">🧠 Kiểm tra kiến thức của bạn</p>
          </div>
        </div>

        <!-- Exit Button với design mới -->
        <button
          class="group relative px-4 py-2 text-gray-600 hover:text-red-600 rounded-xl transition-all duration-300 hover:bg-red-50 border border-transparent hover:border-red-200 hover:shadow-md"
          @click="$emit('exit')"
        >
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="hidden sm:block text-sm font-medium">Thoát</span>
          </div>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
defineProps<{ title: string }>()
defineEmits(['exit'])
</script>
