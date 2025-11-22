<template>
  <a-drawer
    v-model:open="open"
    width="1000"
    title="Study Flashcards"
    destroy-on-close
    class="flashcard-player-drawer"
  >
    <!-- Loading -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-32">
      <a-spin size="large" />
      <p class="mt-6 text-lg text-gray-500">Loading flashcards...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="cards.length === 0" class="flex flex-1 items-center justify-center py-24">
      <div class="text-center">
        <div class="relative mx-auto mb-10 w-40">
          <div class="absolute inset-0 rounded-full bg-gradient-to-br from-orange-100 to-red-100 opacity-60 blur-3xl"></div>
          <svg class="relative mx-auto h-20 w-20 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-800">Empty Deck</h3>
        <p class="mt-4 text-lg text-gray-600 max-w-sm mx-auto">
          This flashcard deck has no cards yet. Please come back later.
        </p>
      </div>
    </div>

    <!-- Main Study Interface -->
    <div v-else class="flex h-full flex-col">
      <!-- Progress Header -->
      <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 p-8 text-white shadow-2xl">
        <div class="absolute inset-0 bg-white/10"></div>
        <div class="relative z-10 flex items-center justify-between">
          <div class="flex items-center gap-6">
            <div class="rounded-2xl bg-white/20 p-4 backdrop-blur-sm">
              <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-2xl font-bold">Study Progress</h3>
              <div class="mt-4 flex items-center gap-8 text-white/90">
                <span class="flex items-center gap-3 rounded-full bg-white/20 px-5 py-2.5 text-base font-semibold backdrop-blur-sm">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  {{ index + 1 }} / {{ cards.length }}
                </span>
                <span class="text-base">
                  <strong>{{ remainingToday }}</strong> cards left today
                </span>
              </div>
            </div>
          </div>

          <a-button
            type="primary"
            size="large"
            @click="openTest = true"
            class="border-0 bg-white/25 px-10 py-6 text-lg font-bold text-white shadow-xl backdrop-blur-sm hover:bg-white/35"
          >
            Take Test
          </a-button>
        </div>

        <!-- Progress Bar -->
        <div class="mt-8">
          <div class="h-4 rounded-full bg-white/25 overflow-hidden">
            <div
              class="h-full rounded-full bg-white shadow-lg transition-all duration-700 ease-out"
              :style="{ width: `${((index + 1) / cards.length) * 100}%` }"
            />
          </div>
        </div>
      </div>

      <!-- 3D Flip Card -->
      <div class="flex flex-1 items-center justify-center px-8 pb-8">
        <div
          class="relative h-96 w-full max-w-3xl cursor-pointer select-none"
          @click="flipCard"
          style="perspective: 1400px"
        >
          <div
            class="relative h-full w-full transition-transform duration-800 ease-out"
            :class="{ 'rotate-y-180': flipped }"
            style="transform-style: preserve-3d"
          >
            <!-- Front: Question -->
            <div class="absolute inset-0 flex flex-col rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-12 text-white shadow-2xl backface-hidden">
              <div class="absolute right-8 top-8 flex h-14 w-14 items-center justify-center rounded-full bg-white/20 text-3xl font-black backdrop-blur-sm border border-white/30">
                Q
              </div>
              <div class="flex flex-1 flex-col justify-center text-center">
                <h2 class="mb-10 text-2xl font-bold uppercase tracking-wider text-white/90">Question</h2>
                <p class="text-3xl font-medium leading-relaxed">{{ currentCard.front }}</p>

                <img
                  v-if="currentCard.image_url"
                  :src="currentCard.image_url"
                  class="mx-auto mt-10 max-h-48 rounded-2xl border-4 border-white/30 object-cover shadow-2xl"
                  alt="Question image"
                />

                <p class="mt-auto pt-12 text-lg italic text-white/80">Click anywhere to reveal answer</p>
              </div>
            </div>

            <!-- Back: Answer -->
            <div class="absolute inset-0 flex flex-col rounded-3xl bg-gradient-to-br from-rose-500 via-pink-500 to-orange-500 p-12 text-white shadow-2xl rotate-y-180 backface-hidden">
              <div class="absolute right-8 top-8 flex h-14 w-14 items-center justify-center rounded-full bg-white/20 text-3xl font-black backdrop-blur-sm border border-white/30">
                A
              </div>
              <div class="flex flex-1 flex-col justify-center text-center">
                <h2 class="mb-10 text-2xl font-bold uppercase tracking-wider text-white/90">Answer</h2>
                <p class="text-3xl font-medium leading-relaxed">{{ currentCard.back }}</p>

                <div v-if="currentCard.audio_url" class="mx-auto mt-10 w-full max-w-md">
                  <audio controls class="w-full rounded-2xl shadow-lg">
                    <source :src="currentCard.audio_url" type="audio/mpeg" />
                    Your browser does not support audio.
                  </audio>
                </div>

                <p class="mt-auto pt-12 text-lg italic text-white/80">Click to go back</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Controls -->
      <div class="px-10 pb-8">
        <div class="flex items-center justify-between rounded-3xl bg-white p-8 shadow-2xl">
          <a-button
            size="large"
            :disabled="index === 0"
            @click="prevCard"
            class="flex items-center gap-4 rounded-2xl border-0 bg-gradient-to-r from-indigo-600 to-purple-700 px-10 py-7 text-xl font-bold text-white shadow-xl transition-all hover:-translate-y-1 hover:shadow-2xl disabled:cursor-not-allowed disabled:opacity-50"
          >
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Previous
          </a-button>

          <div class="text-center">
            <div class="text-3xl font-black text-gray-800">{{ index + 1 }} / {{ cards.length }}</div>
            <div class="mt-4 flex justify-center gap-2">
              <div
                v-for="n in cards.length"
                :key="n"
                class="h-3 w-3 rounded-full transition-all duration-300"
                :class="n === index + 1 ? 'scale-150 bg-gradient-to-r from-indigo-500 to-pink-500 shadow-lg' : 'bg-gray-300'"
              />
            </div>
          </div>

          <a-button
            size="large"
            :disabled="index >= cards.length - 1"
            @click="nextCard"
            class="flex items-center gap-4 rounded-2xl border-0 bg-gradient-to-r from-cyan-500 to-teal-600 px-10 py-7 text-xl font-bold text-white shadow-xl transition-all hover:-translate-y-1 hover:shadow-2xl disabled:cursor-not-allowed disabled:opacity-50"
          >
            Next
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a-button>
        </div>

        <p class="mt-8 text-center text-base text-gray-600">
          Tip: <strong>Tip:</strong> Read → Think → Flip → Self-assess → Continue
        </p>
      </div>
    </div>

    <!-- Test Modal -->
    <FlashcardLearnTest v-model:open="openTest" :set-id="setId" />
  </a-drawer>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { flashcardLearnApi } from '@/api/customer/flashcardLearnApi'
import type { Flashcard } from '@/types/Flashcard'
import FlashcardLearnTest from './FlashcardLearnTest.vue'

const props = defineProps<{
  open: boolean
  setId: number | null
}>()

const emit = defineEmits(['update:open'])

const open = ref(props.open)
watch(() => props.open, v => (open.value = v))
watch(open, v => emit('update:open', v))

const cards = ref<Flashcard[]>([])
const loading = ref(false)
const index = ref(0)
const flipped = ref(false)
const openTest = ref(false)

const currentCard = computed(() => cards.value[index.value] ?? {})
const remainingToday = computed(() => Math.max(cards.value.length - index.value - 1, 0))

watch(
  () => props.setId,
  async id => {
    if (id) await loadCards(id)
  },
  { immediate: true }
)

const loadCards = async (setId: number) => {
  loading.value = true
  try {
    const data = await flashcardLearnApi.getFlashcardsBySet(setId)
    cards.value = data
    index.value = 0
    flipped.value = false
  } finally {
    loading.value = false
  }
}

const flipCard = () => {
  if (!currentCard.value.id) return
  flipped.value = !flipped.value
  flashcardLearnApi.logReview(currentCard.value.id)
}

const nextCard = () => {
  if (index.value < cards.value.length - 1) {
    index.value++
    flipped.value = false
  }
}

const prevCard = () => {
  if (index.value > 0) {
    index.value--
    flipped.value = false
  }
}
</script>

<style scoped>
.rotate-y-180 {
  transform: rotateY(180deg);
}
.backface-hidden {
  backface-visibility: hidden;
}
</style>