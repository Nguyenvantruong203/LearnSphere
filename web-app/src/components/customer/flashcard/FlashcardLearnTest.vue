<template>
  <a-drawer
    v-model:open="open"
    width="1000"
    title="Memory Test"
    destroy-on-close
    class="flashcard-test-drawer"
  >
    <!-- Loading -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-32">
      <a-spin size="large" />
      <p class="mt-6 text-lg text-gray-500">Loading test...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="testCards.length === 0" class="flex flex-1 items-center justify-center py-24">
      <div class="text-center max-w-md">
        <div class="relative mb-10">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="h-32 w-32 rounded-full bg-gradient-to-br from-yellow-100 to-orange-100 opacity-50 blur-xl"></div>
          </div>
          <svg class="relative mx-auto h-16 w-16 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">No cards to test</h3>
        <p class="text-gray-600">There are no flashcards available for testing right now.</p>
      </div>
    </div>

    <!-- Test Interface -->
    <div v-else class="flex h-full flex-col">
      <!-- Header + Stats -->
      <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 p-8 text-white shadow-2xl">
        <div class="absolute inset-0 bg-white/10"></div>

        <div class="relative flex items-center justify-between">
          <div class="flex items-center gap-6">
            <div class="rounded-2xl bg-white/20 p-4 backdrop-blur-sm">
              <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
            <div>
              <h3 class="text-2xl font-bold">Question {{ index + 1 }} / {{ testCards.length }}</h3>
              <div class="mt-4 flex items-center gap-6 text-white/90">
                <span class="flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-base font-semibold backdrop-blur-sm">
                  <svg class="h-6 w-6 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ correct }} Correct
                </span>
                <span class="flex items-center gap-2 rounded-full bg-white/20 px-5 py-2 text-base font-semibold backdrop-blur-sm">
                  <svg class="h-6 w-6 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  {{ incorrect }} Wrong
                </span>
              </div>
            </div>
          </div>

          <!-- Mode Switch -->
          <div class="flex gap-3">
            <button
              v-for="m in modes"
              :key="m.value"
              @click="switchMode(m.value)"
              class="rounded-xl px-6 py-3 text-sm font-medium transition-all"
              :class="activeMode === m.value
                ? 'bg-purple-100 text-purple-700 border border-purple-500 shadow-md'
                : 'bg-white/15 text-white/90 border border-white/30 hover:bg-white/25'"
            >
              {{ m.label }}
            </button>
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-8">
          <div class="h-4 rounded-full bg-white/25 overflow-hidden">
            <div
              class="h-full rounded-full bg-white transition-all duration-700 ease-out shadow-lg"
              :style="{ width: `${((index + 1) / testCards.length) * 100}%` }"
            />
          </div>
        </div>
      </div>

      <!-- Results Screen -->
      <div v-if="finished" class="flex flex-1 items-center justify-center px-8">
        <div class="w-full max-w-2xl text-center">
          <div class="relative mb-12">
            <div class="absolute inset-0 mx-auto h-40 w-40 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 opacity-60 blur-3xl"></div>
            <svg class="relative mx-auto h-24 w-24 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <h2 class="mb-8 text-4xl font-black text-gray-900">Test Complete!</h2>

          <div class="grid grid-cols-2 gap-8 mb-10">
            <div class="rounded-3xl bg-gradient-to-br from-green-50 to-emerald-50 p-8 border-2 border-green-200 shadow-lg">
              <div class="text-5xl font-black text-green-600 mb-3">{{ correct }}</div>
              <div class="text-lg font-semibold text-green-700">Correct</div>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-red-50 to-pink-50 p-8 border-2 border-red-200 shadow-lg">
              <div class="text-5xl font-black text-red-600 mb-3">{{ incorrect }}</div>
              <div class="text-lg font-semibold text-red-700">Needs Review</div>
            </div>
          </div>

          <div class="mb-12">
            <div class="text-6xl font-black text-purple-600">
              {{ Math.round((correct / testCards.length) * 100) }}%
            </div>
            <p class="mt-3 text-xl text-gray-600">Accuracy Rate</p>
          </div>

          <div class="flex flex-col gap-4 max-w-sm mx-auto">
            <a-button
              type="primary"
              size="large"
              @click="retry"
              class="h-14 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-lg font-bold shadow-xl hover:shadow-2xl"
            >
              <svg class="inline-block w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Retake Test
            </a-button>
            <a-button size="large" @click="open = false" class="h-14 rounded-2xl text-lg font-medium">
              Close
            </a-button>
          </div>
        </div>
      </div>

      <!-- Question Screen -->
      <div v-else class="flex flex-1 flex-col px-8 pb-8">
        <!-- Question Card -->
        <div class="mb-8 rounded-3xl bg-white p-12 text-center shadow-2xl border">
          <p class="text-3xl font-bold text-gray-900 leading-relaxed">{{ current.front }}</p>
        </div>

        <!-- Typing Mode -->
        <div v-if="activeMode === 'type'" class="space-y-8">
          <div class="rounded-3xl bg-white p-8 shadow-2xl border-2 border-transparent bg-gradient-to-br from-slate-50 to-gray-50">
            <label class="mb-4 flex items-center text-lg font-semibold text-gray-700">
              <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z" />
              </svg>
              Type your answer:
            </label>
            <a-input
              v-model:value="userAnswer"
              placeholder="Enter your answer..."
              size="large"
              class="h-16 rounded-2xl border-2 border-transparent bg-white/80 text-lg shadow-inner focus:border-purple-500 focus:ring-4 focus:ring-purple-200"
              @press-enter="checkTypingAnswer"
              :disabled="answered"
            />
          </div>

          <!-- Feedback -->
          <div v-if="answered" class="rounded-3xl p-10 text-center shadow-2xl" :class="isCorrect ? 'bg-green-50 border-2 border-green-300' : 'bg-red-50 border-2 border-red-300'">
            <svg v-if="isCorrect" class="mx-auto mb-4 h-20 w-20 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-else class="mx-auto mb-4 h-20 w-20 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <p class="text-2xl font-bold" :class="isCorrect ? 'text-green-700' : 'text-red-700'">
              {{ isCorrect ? 'Correct!' : 'Not quite' }}
            </p>
            <p v-if="!isCorrect" class="mt-4 text-lg text-gray-700">
              Correct answer: <span class="font-bold text-gray-900">{{ current.back }}</span>
            </p>
          </div>
        </div>

        <!-- Multiple Choice Mode -->
        <div v-else-if="activeMode === 'choice'" class="space-y-5">
          <div class="rounded-3xl bg-white p-8 shadow-2xl border-2 border-transparent bg-gradient-to-br from-slate-50 to-gray-50">
            <label class="mb-6 flex items-center text-lg font-semibold text-gray-700">
              <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
              Choose the correct answer:
            </label>

            <div class="space-y-4">
              <button
                v-for="(opt, i) in choiceOptions"
                :key="opt"
                @click="selectChoice(opt)"
                :disabled="answered"
                class="flex w-full items-center rounded-2xl border-2 p-6 text-left text-lg font-medium transition-all hover:-translate-y-1 hover:shadow-xl disabled:cursor-not-allowed"
                :class="choiceButtonClasses(opt, i)"
              >
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-xl font-bold"
                  :class="choiceLetterClasses(opt, i)">
                  {{ String.fromCharCode(65 + i) }}
                </div>
                <div class="ml-6 flex-1">{{ opt }}</div>

                <div v-if="answered && opt === current.back" class="ml-4 flex h-10 w-10 items-center justify-center rounded-full bg-green-500 text-white">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div v-else-if="answered && selectedChoice === opt && opt !== current.back"
                  class="ml-4 flex h-10 w-10 items-center justify-center rounded-full bg-red-500 text-white">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </div>
              </button>
            </div>
          </div>
        </div>

        <!-- Next / Check Button -->
        <div class="mt-auto flex justify-end">
          <a-button
            type="primary"
            size="large"
            @click="answered ? goNext() : (activeMode === 'type' ? checkTypingAnswer() : null)"
            :disabled="activeMode === 'choice' && !answered"
            class="h-14 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 px-10 text-lg font-bold shadow-xl hover:shadow-2xl"
          >
            {{ answered ? 'Next' : 'Check' }}
            <svg class="ml-3 inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a-button>
        </div>
      </div>
    </div>
  </a-drawer>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { flashcardLearnApi } from '@/api/customer/flashcardLearnApi'
import type { Flashcard } from '@/types/Flashcard'

const props = defineProps<{ open: boolean; setId: number | null }>()
const emit = defineEmits(['update:open'])

const open = ref(props.open)
watch(() => props.open, v => open.value = v)
watch(open, v => emit('update:open', v))

const modes = [
  { value: 'type', label: 'Type Answer' },
  { value: 'choice', label: 'Multiple Choice' }
] as const
type Mode = (typeof modes)[number]['value']
const activeMode = ref<Mode>('type')

const testCards = ref<Flashcard[]>([])
const loading = ref(false)
const index = ref(0)
const finished = ref(false)
const correct = ref(0)
const incorrect = ref(0)

const current = computed(() => testCards.value[index.value])

// Typing
const userAnswer = ref('')
const answered = ref(false)
const isCorrect = ref<boolean | null>(null)

const normalize = (s: string) => (s || '').toLowerCase().trim().replace(/\s+/g, ' ')

// Multiple Choice
const choiceOptions = ref<string[]>([])
const selectedChoice = ref<string | null>(null)

const shuffle = <T>(arr: T[]) => [...arr].sort(() => Math.random() - 0.5)

watch(() => props.setId, id => { if (id && open.value) loadCards(id) })
watch(() => open.value, val => { if (val && props.setId) loadCards(props.setId!) })

const loadCards = async (setId: number) => {
  loading.value = true
  try {
    const data = await flashcardLearnApi.getFlashcardsBySet(setId)
    testCards.value = shuffle(data)
    index.value = 0
    resetState()
    prepareChoices()
  } finally {
    loading.value = false
  }
}

const resetState = () => {
  finished.value = false
  correct.value = 0
  incorrect.value = 0
  answered.value = false
  isCorrect.value = null
  selectedChoice.value = null
  choiceOptions.value = []
  userAnswer.value = ''
}

const switchMode = (mode: Mode) => {
  activeMode.value = mode
  answered.value = false
  isCorrect.value = null
  selectedChoice.value = null
  userAnswer.value = ''
  if (mode === 'choice') prepareChoices()
}

// Typing Mode
const checkTypingAnswer = () => {
  const ok = normalize(current.value!.back) === normalize(userAnswer.value)
  isCorrect.value = ok
  answered.value = true
  if (ok) correct.value++
  else incorrect.value++
  flashcardLearnApi.logReview(current.value!.id)
}

const goNext = () => {
  if (index.value < testCards.value.length - 1) {
    index.value++
    answered.value = false
    isCorrect.value = null
    selectedChoice.value = null
    userAnswer.value = ''
    if (activeMode.value === 'choice') prepareChoices()
  } else {
    finished.value = true
  }
}

// Choice Mode
const prepareChoices = () => {
  if (!current.value) return
  const others = testCards.value.filter(c => c.id !== current.value!.id).map(c => c.back)
  const distractors = shuffle(others).slice(0, 3)
  choiceOptions.value = shuffle([current.value!.back, ...distractors])
}

const selectChoice = (opt: string) => {
  if (answered.value) return
  selectedChoice.value = opt
  const ok = opt === current.value!.back
  isCorrect.value = ok
  answered.value = true
  if (ok) correct.value++
  else incorrect.value++
  flashcardLearnApi.logReview(current.value!.id)
}

const choiceButtonClasses = (opt: string, i: number) => {
  if (!answered.value) {
    return selectedChoice.value === opt
      ? 'border-purple-500 bg-purple-50 shadow-lg'
      : 'border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-50'
  }
  if (opt === current.value!.back) return 'border-green-500 bg-green-50'
  if (opt === selectedChoice.value) return 'border-red-500 bg-red-50'
  return 'border-gray-300 bg-gray-50 opacity-60'
}

const choiceLetterClasses = (opt: string, i: number) => {
  if (!answered.value) {
    return selectedChoice.value === opt
      ? 'bg-purple-600 text-white'
      : 'bg-gray-200 text-gray-700'
  }
  if (opt === current.value!.back) return 'bg-green-600 text-white'
  if (opt === selectedChoice.value) return 'bg-red-600 text-white'
  return 'bg-gray-300 text-gray-600'
}

const retry = () => {
  index.value = 0
  resetState()
  testCards.value = shuffle(testCards.value)
  if (activeMode.value === 'choice') prepareChoices()
}
</script>