<template>
  <a-drawer
    v-model:open="open"
    title="Flashcard Library"
    width="800"
    destroy-on-close
    class="flashcard-learn-drawer"
  >
    <!-- Stunning Header -->
    <div class="relative overflow-hidden mb-10 rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-10 text-white shadow-2xl">
      <!-- Subtle grid pattern -->
      <div class="absolute inset-0 bg-[url(data:image/svg+xml,%3Csvg%20width=%2260%22%20height=%2260%22%20viewBox=%220%200%2060%22%20xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg%20fill=%22none%22%20fill-rule=%22evenodd%22%3E%3Cg%20fill=%22%23ffffff%22%20fill-opacity=%220.08%22%3E%3Cpath%20d=%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2zM18%2018h4v4h-4zM14%2034h4v4h-4z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E)]"></div>
      
      <!-- Floating orbs -->
      <div class="absolute top-6 right-6 h-20 w-20 rounded-full bg-white/10 blur-2xl animate-pulse"></div>
      <div class="absolute bottom-8 left-8 h-14 w-14 rounded-full bg-white/20 blur-xl animate-ping"></div>

      <div class="relative z-10">
        <div class="flex items-start gap-6 mb-10">
          <div class="relative">
            <div class="rounded-2xl border border-white/30 bg-white/20 p-5 backdrop-blur-md">
              <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              <div class="absolute -top-2 -right-2 h-7 w-7 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 shadow-lg animate-pulse"></div>
            </div>
          </div>

          <div>
            <h3 class="mb-3 bg-gradient-to-r from-white to-white/80 bg-clip-text text-3xl font-black text-transparent">
              Flashcard Library
            </h3>
            <p class="text-white/90 text-lg">Master any topic with smart, interactive flashcards</p>
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-10 text-center">
          <div>
            <div class="text-5xl font-black">{{ flashcardSets?.length || 0 }}</div>
            <div class="mt-2 text-sm font-medium uppercase tracking-wider text-white/70">Decks Available</div>
          </div>
          <div>
            <div class="text-5xl font-black text-yellow-300">100%</div>
            <div class="mt-2 text-sm font-medium uppercase tracking-wider text-white/70">Ready to Study</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!flashcardSets?.length" class="flex flex-col items-center justify-center py-32">
      <div class="relative mb-10">
        <div class="absolute inset-0 -translate-x-1/2 -translate-y-1/2 w-40 h-40 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 opacity-50 blur-3xl"></div>
        <svg class="relative z-10 h-20 w-20 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-gray-800 mb-3">No Flashcards Yet</h3>
      <p class="text-center text-gray-600 max-w-sm leading-relaxed">
        This topic doesn't have any flashcard decks yet. Please check back later when your instructor adds content.
      </p>
    </div>

    <!-- Flashcard Decks List -->
    <div v-else class="space-y-6 pb-6">
      <div
        v-for="(set, i) in flashcardSets"
        :key="set.id"
        @click="openPlayer(set.id)"
        class="group cursor-pointer overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-md transition-all hover:-translate-y-2 hover:shadow-2xl"
      >
        <!-- Gradient top bar -->
        <div class="h-1.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <div class="flex items-center gap-6 p-8">
          <!-- Index + checkmark -->
          <div class="relative flex-shrink-0">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-3xl font-black text-white shadow-xl">
              {{ i + 1 }}
            </div>
            <div class="absolute -top-2 -right-2 flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg">
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <h3 class="truncate text-xl font-bold text-gray-900 mb-2">{{ set.title }}</h3>
            <div class="flex items-center gap-2 text-sm font-medium text-emerald-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Ready to Study
            </div>
            <p class="mt-3 text-gray-500">Click to start reviewing with interactive flashcards</p>
          </div>

          <!-- Arrow -->
          <svg class="h-8 w-8 text-gray-400 transition group-hover:text-indigo-600 group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Player Modal -->
    <FlashcardLearnPlayer v-model:open="player.open" :set-id="player.setId" />
  </a-drawer>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import FlashcardLearnPlayer from './FlashcardLearnPlayer.vue'

const props = defineProps<{
  open: boolean
  flashcardSets: any[] | null
}>()

const emit = defineEmits(['update:open'])

const open = ref(props.open)
watch(() => props.open, v => open.value = v)
watch(open, v => emit('update:open', v))

const player = ref({
  open: false,
  setId: null as number | null
})

const openPlayer = (setId: number) => {
  player.value = { open: true, setId }
}
</script>