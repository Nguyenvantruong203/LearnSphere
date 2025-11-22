<template>
  <header class="bg-white/95 backdrop-blur-md border-b border-gray-200/60 shadow-lg sticky top-0 z-50">
    <div class="px-6 md:px-8 py-5">
      <div class="flex items-center justify-between">
        <!-- Left: Icon + Title -->
        <div class="flex items-center gap-5 group">
          <div
            class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#49bbbd] via-[#2ea5a8] to-[#65d6d8] shadow-lg transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl">
            <!-- Hover glow overlay -->
            <div
              class="absolute inset-0 rounded-2xl bg-gradient-to-br from-[#2ea5a8] via-[#49bbbd] to-[#68d6d8] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            </div>

            <!-- Icon -->
            <svg class="relative z-10 h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>

          <div>
            <h1
              class="bg-gradient-to-r from-[#2ea5a8] to-[#49bbbd] bg-clip-text text-2xl font-black text-transparent md:text-3xl">
              {{ title }}
            </h1>
            <p class="mt-1 text-sm font-medium text-gray-500 md:text-base">
              Test your knowledge
            </p>
          </div>
        </div>

        <!-- Exit Button -->
        <button
          class="group flex items-center gap-2.5 rounded-xl border border-transparent px-5 py-3 text-gray-600 transition-all duration-300 hover:border-red-200 hover:bg-red-50 hover:text-red-600 hover:shadow-md"
          @click="$emit('exit')">
          <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span class="hidden text-sm font-semibold sm:block">Exit</span>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
defineProps<{
  title: string
}>()

defineEmits<{
  exit: []
}>()
</script>