<template>
  <a-modal v-model:open="innerOpen" :title="`Flashcards — ${set?.title || ''}`" width="1400px" :footer="null"
    destroy-on-close wrap-class-name="flashcard-manager-fixed-modal" :mask-closable="false"
    class="flashcard-manager-modal" :class="{ 'preview-mode': previewMode }">
    <!-- Loading State -->
    <div v-if="loading" class="flex h-96 flex-col items-center justify-center">
      <a-spin size="large" />
      <p class="mt-4 text-base text-gray-500">Loading flashcards...</p>
    </div>

    <div v-else class="flex h-[680px] flex-col">
      <!-- Fixed Header -->
      <div
        class="mb-6 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
              <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold">Flashcard Set</h3>
              <div class="mt-2 flex items-center gap-4 text-sm">
                <span class="rounded-full bg-white/20 px-3 py-1 font-medium">
                  {{ cards.length }} card{{ cards.length !== 1 ? 's' : '' }}
                </span>
                <span class="opacity-90">
                  Tip: Press <kbd class="mx-1 rounded bg-white/20 px-2 py-1 text-xs">Ctrl+S</kbd> to save
                </span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <a-button :type="previewMode ? 'default' : 'text'" size="large" @click="previewMode = !previewMode"
              class="backdrop-blur-sm transition-all"
              :class="previewMode ? 'bg-white/20 border-white/30 text-white hover:bg-white/30' : 'text-white/80 hover:bg-white/10'">
              <component :is="previewMode ? 'EditOutlined' : 'EyeOutlined'" class="mr-2" />
              {{ previewMode ? 'Edit Mode' : 'Preview' }}
            </a-button>

            <a-button type="primary" size="large" @click="addNewCard"
              class="border-0 bg-white font-semibold text-indigo-600 shadow-lg hover:bg-gray-100">
              <PlusOutlined class="mr-2" /> Add Card
            </a-button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="cards.length === 0" class="flex flex-1 items-center justify-center">
        <div class="text-center">
          <div class="relative mb-8">
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="h-32 w-32 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 opacity-50"></div>
            </div>
            <svg class="relative mx-auto h-14 w-14 text-indigo-500" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <h3 class="mb-3 text-xl font-bold text-gray-900">Start Creating Flashcards</h3>
          <p class="mx-auto mb-8 max-w-md text-sm text-gray-600">
            Create interactive study cards to help students remember knowledge effectively
          </p>
          <a-button type="primary" size="large" @click="addNewCard"
            class="h-12 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-8 text-base font-semibold shadow-lg hover:shadow-xl">
            <PlusOutlined class="mr-2" /> Create First Card
          </a-button>
        </div>
      </div>

      <!-- Scrollable Content Area -->
      <div v-else class="flex-1 overflow-hidden">
        <div class="h-full overflow-y-auto pr-4 pb-4 custom-scrollbar">
          <!-- Search Bar (Sticky) -->
          <div class="sticky top-0 z-10 mb-5 bg-white/90 pb-4 backdrop-blur-sm">
            <div class="flex items-center gap-4">
              <a-input-search v-model:value="searchQuery" placeholder="Search card content..." size="large" allow-clear
                class="flex-1 rounded-xl border-2 border-gray-200 focus:border-indigo-500">
                <template #prefix>
                  <SearchOutlined class="text-gray-400" />
                </template>
              </a-input-search>
              <div class="flex items-center gap-2 text-sm text-gray-500">
                Showing:
                <a-tag :color="searchQuery ? 'blue' : 'default'">
                  {{ filteredCards.length }}/{{ cards.length }}
                </a-tag>
              </div>
            </div>
          </div>

          <!-- Card List -->
          <TransitionGroup name="card-list" tag="div" class="space-y-6">
            <div v-for="(card, index) in filteredCards" :key="card.local_id" class="group">
              <!-- Preview Mode -->
              <div v-if="previewMode" @click="flipCard(card)"
                class="flashcard-preview group relative h-72 cursor-pointer overflow-hidden rounded-2xl shadow-lg transition-transform hover:-translate-y-1">
                <div class="flashcard-inner relative h-full w-full transition-transform duration-700"
                  :class="{ 'flipped': card.isFlipped }" style="transform-style: preserve-3d;">
                  <!-- Front -->
                  <div
                    class="flashcard-side absolute inset-0 flex flex-col rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white backface-hidden">
                    <div
                      class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-sm font-bold backdrop-blur-sm">
                      {{ index + 1 }}
                    </div>
                    <div class="flex flex-1 flex-col justify-center">
                      <div class="mb-4 text-center text-xs font-semibold uppercase tracking-wider opacity-90">Question
                      </div>
                      <p class="text-center text-lg font-medium leading-relaxed">{{ card.front || 'No content' }}</p>
                      <div v-if="card.image_url" class="mt-4">
                        <img :src="card.image_url" class="h-32 w-full rounded-lg object-cover border-2 border-white/30"
                          alt="Image" />
                      </div>
                    </div>
                    <div class="text-center text-xs opacity-80">Click to flip</div>
                  </div>

                  <!-- Back -->
                  <div
                    class="flashcard-side absolute inset-0 flex flex-col rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white backface-hidden"
                    style="transform: rotateY(180deg);">
                    <div
                      class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-sm font-bold backdrop-blur-sm">
                      {{ index + 1 }}
                    </div>
                    <div class="flex flex-1 flex-col justify-center">
                      <div class="mb-4 text-center text-xs font-semibold uppercase tracking-wider opacity-90">Answer
                      </div>
                      <p class="text-center text-lg font-medium leading-relaxed">{{ card.back || 'No content' }}</p>
                      <div v-if="card.audio_url" class="mt-4">
                        <audio controls class="w-full rounded-lg bg-white/20">
                          <source :src="card.audio_url" />
                        </audio>
                      </div>
                    </div>
                    <div class="text-center text-xs opacity-80">Click to flip back</div>
                  </div>
                </div>
              </div>

              <!-- Edit Mode -->
              <div v-else class="overflow-hidden rounded-2xl border bg-white shadow-sm transition-all hover:shadow-md">
                <!-- Card Header -->
                <div class="border-b border-gray-100 p-5">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                      <div
                        class="relative flex h-14 w-14 items-center justify-center rounded-xl text-xl font-bold text-white"
                        :class="card.id ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-amber-500 to-orange-600'">
                        {{ index + 1 }}
                        <div
                          class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full border-2 border-white"
                          :class="card.id ? 'bg-emerald-500' : 'bg-amber-500'">
                          <component :is="card.id ? 'CheckOutlined' : 'ClockCircleOutlined'"
                            class="text-white text-xs" />
                        </div>
                      </div>
                      <div>
                        <h4 class="text-base font-bold text-gray-900">Flashcard #{{ index + 1 }}</h4>
                        <span class="inline-block rounded-full px-3 py-1 text-xs font-medium"
                          :class="card.id ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                          {{ card.id ? 'Saved' : 'Draft' }}
                        </span>
                      </div>
                    </div>

                    <div class="flex items-center gap-2">
                      <a-tooltip title="Preview Card">
                        <a-button type="text" @click="quickPreview(card)" class="text-gray-600 hover:bg-indigo-50">
                          <EyeOutlined class="text-lg" />
                        </a-button>
                      </a-tooltip>

                      <a-button type="primary" @click="saveCard(card)" :loading="card.saving"
                        :disabled="!card.front?.trim() || !card.back?.trim()"
                        class="rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 font-medium shadow hover:shadow-md">
                        <SaveOutlined class="mr-1" />
                        {{ card.saving ? 'Saving...' : 'Save' }}
                      </a-button>

                      <a-popconfirm v-if="card.id" title="Delete this card?" description="This action cannot be undone"
                        ok-text="Delete" cancel-text="Cancel" ok-type="danger" @confirm="deleteCard(card)">
                        <a-button danger type="text" class="hover:bg-red-50">
                          <DeleteOutlined class="text-lg" />
                        </a-button>
                      </a-popconfirm>
                      <a-button v-else danger type="text" @click="removeUnsavedCard(card)" class="hover:bg-red-50">
                        <DeleteOutlined class="text-lg" />
                      </a-button>
                    </div>
                  </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                  <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Front Side -->
                    <div>
                      <div class="mb-3 flex items-center gap-3">
                        <div
                          class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-lg font-bold">
                          Q</div>
                        <div class="text-base font-semibold text-gray-800">Front Side <span
                            class="text-red-500">*</span></div>
                        <div class="ml-auto text-xs italic text-gray-500">Question or term</div>
                      </div>
                      <a-textarea v-model:value="card.front" placeholder="e.g., What is Vue.js?" :rows="4" show-count
                        :maxlength="500" @keydown="handleKeyDown($event, card)"
                        class="rounded-xl border-2 border-gray-200 text-base focus:border-indigo-500"
                        :class="{ 'border-red-400': !card.front?.trim() }" />

                      <div v-if="card.image_url" class="mt-4 rounded-lg border bg-gray-50 p-3">
                        <div class="flex items-center justify-between mb-2">
                          <span class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <PictureOutlined /> Attached Image
                          </span>
                          <a-button type="text" size="small" @click="card.image_url = ''"
                            class="text-red-500 hover:bg-red-50">
                            <CloseOutlined />
                          </a-button>
                        </div>
                        <img :src="card.image_url" class="h-40 w-full rounded object-cover"
                          @error="card.image_url = ''" />
                      </div>
                    </div>

                    <!-- Back Side -->
                    <div>
                      <div class="mb-3 flex items-center gap-3">
                        <div
                          class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-lg font-bold">
                          A</div>
                        <div class="text-base font-semibold text-gray-800">Back Side <span class="text-red-500">*</span>
                        </div>
                        <div class="ml-auto text-xs italic text-gray-500">Answer or definition</div>
                      </div>
                      <a-textarea v-model:value="card.back" placeholder="e.g., A progressive JavaScript framework..."
                        :rows="4" show-count :maxlength="500" @keydown="handleKeyDown($event, card)"
                        class="rounded-xl border-2 border-gray-200 text-base focus:border-emerald-500"
                        :class="{ 'border-red-400': !card.back?.trim() }" />

                      <div v-if="card.audio_url" class="mt-4 rounded-lg border bg-gray-50 p-4">
                        <div class="flex items-center justify-between mb-2">
                          <span class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <SoundOutlined /> Attached Audio
                          </span>
                          <a-button type="text" size="small" @click="card.audio_url = ''"
                            class="text-red-500 hover:bg-red-50">
                            <CloseOutlined />
                          </a-button>
                        </div>
                        <audio controls class="w-full">
                          <source :src="card.audio_url" />
                        </audio>
                      </div>
                    </div>
                  </div>

                  <!-- Media Inputs -->
                  <div class="mt-8 rounded-2xl border bg-gray-50 p-6">
                    <div class="text-center mb-5">
                      <h4 class="text-base font-bold text-gray-800">Attached Media</h4>
                      <p class="text-xs text-gray-600">Add image or audio to make cards more engaging</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                      <div>
                        <label class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700">
                          <PictureOutlined class="text-indigo-600" /> Image URL
                        </label>
                        <a-input v-model:value="card.image_url" placeholder="https://example.com/image.jpg"
                          class="rounded-lg border-2 border-gray-200 focus:border-indigo-500" />
                      </div>
                      <div>
                        <label class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700">
                          <SoundOutlined class="text-indigo-600" /> Audio URL
                        </label>
                        <a-input v-model:value="card.audio_url" placeholder="https://example.com/audio.mp3"
                          class="rounded-lg border-2 border-gray-200 focus:border-indigo-500" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>
      </div>
    </div>

    <!-- Quick Preview Modal -->
    <a-modal v-model:open="quickPreviewModal.open" title="Card Preview" :footer="null" width="500px">
      <div v-if="quickPreviewModal.card" class="h-80 cursor-pointer" @click="flipCard(quickPreviewModal.card)">
        <div class="flashcard-inner-large relative h-full w-full transition-transform duration-700"
          :class="{ 'flipped': quickPreviewModal.card.isFlipped }" style="transform-style: preserve-3d;">
          <div
            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 text-white backface-hidden">
            <div class="text-sm font-medium uppercase text-white/90 mb-4">Front</div>
            <p class="text-center text-lg font-medium">{{ quickPreviewModal.card.front || 'No content' }}</p>
            <div v-if="quickPreviewModal.card.image_url" class="mt-6">
              <img :src="quickPreviewModal.card.image_url" class="mx-auto h-32 rounded-lg object-cover" />
            </div>
          </div>
          <div
            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-8 text-white backface-hidden"
            style="transform: rotateY(180deg);">
            <div class="text-sm font-medium uppercase text-white/90 mb-4">Back</div>
            <p class="text-center text-lg font-medium">{{ quickPreviewModal.card.back || 'No content' }}</p>
          </div>
        </div>
      </div>
      <p class="mt-4 text-center text-sm text-gray-500">Click to flip</p>
    </a-modal>
  </a-modal>
</template>

<script setup lang="ts">
// Giữ nguyên script như cũ (không thay đổi logic)
import { ref, watch, computed } from 'vue'
import { notification } from 'ant-design-vue'
import {
  PlusOutlined, SaveOutlined, DeleteOutlined, EyeOutlined,
  SearchOutlined, CloseOutlined, PictureOutlined, SoundOutlined,
  CheckOutlined, ClockCircleOutlined
} from '@ant-design/icons-vue'
import { flashcardApi } from '@/api/instructor/flashcardApi'

const props = defineProps<{ open: boolean; set: any | null }>()
const emit = defineEmits(['update:open', 'updated'])

const innerOpen = ref(props.open)
watch(() => props.open, v => innerOpen.value = v)
watch(innerOpen, v => emit('update:open', v))

const set = ref(props.set)
watch(() => props.set, val => {
  set.value = val
  if (val) loadCards()
})

const loading = ref(false)
const cards = ref<any[]>([])
const previewMode = ref(false)
const searchQuery = ref('')
const quickPreviewModal = ref({ open: false, card: null as any })

let tempId = 1

const filteredCards = computed(() => {
  if (!searchQuery.value.trim()) return cards.value
  const q = searchQuery.value.toLowerCase()
  return cards.value.filter(c =>
    c.front?.toLowerCase().includes(q) ||
    c.back?.toLowerCase().includes(q)
  )
})

const loadCards = async () => {
  if (!set.value?.id) return
  loading.value = true
  try {
    const res = await flashcardApi.getBySet(set.value.id)
    cards.value = res.map((c: any) => ({
      ...c,
      local_id: c.id,
      saving: false,
      isFlipped: false
    }))
  } finally {
    loading.value = false
  }
}

const addNewCard = () => {
  cards.value.unshift({
    id: null,
    local_id: `temp-${tempId++}`,
    flashcard_set_id: set.value.id,
    front: "", back: "", image_url: "", audio_url: "",
    saving: false, isFlipped: false
  })
}

const flipCard = (card: any) => card.isFlipped = !card.isFlipped

const quickPreview = (card: any) => {
  quickPreviewModal.value = { open: true, card: { ...card, isFlipped: false } }
}

const handleKeyDown = (e: KeyboardEvent, card: any) => {
  if (e.ctrlKey && e.key === 's') { e.preventDefault(); saveCard(card) }
}

const saveCard = async (card: any) => {
  if (!card.front?.trim() || !card.back?.trim()) {
    notification.error({ message: "Front and back are required" })
    return
  }
  card.saving = true
  try {
    let res
    if (card.id) {
      res = await flashcardApi.update(card.id, { front: card.front.trim(), back: card.back.trim(), image_url: card.image_url?.trim() || null, audio_url: card.audio_url?.trim() || null })
      notification.success({ message: "Card updated" })
    } else {
      res = await flashcardApi.create(set.value.id, { front: card.front.trim(), back: card.back.trim(), image_url: card.image_url?.trim() || null, audio_url: card.audio_url?.trim() || null })
      notification.success({ message: "Card created" })
    }
    Object.assign(card, res)
    card.local_id = res.id
    card.isFlipped = false
    emit('updated')
  } catch (e: any) {
    notification.error({ message: e?.message || "Save failed" })
  } finally {
    card.saving = false
  }
}

const deleteCard = async (card: any) => {
  try {
    await flashcardApi.delete(card.id)
    notification.success({ message: "Card deleted" })
    cards.value = cards.value.filter(c => c.local_id !== card.local_id)
    emit('updated')
  } catch {
    notification.error({ message: "Delete failed" })
  }
}

const removeUnsavedCard = (card: any) => {
  cards.value = cards.value.filter(c => c.local_id !== card.local_id)
}

watch(innerOpen, val => {
  if (val && set.value?.id) loadCards()
})
</script>

<style scoped>
.backface-hidden {
  backface-visibility: hidden;
}

.flashcard-inner {
  transform-style: preserve-3d;
}

.flashcard-inner.flipped {
  transform: rotateY(180deg);
}

.flashcard-inner-large.flipped {
  transform: rotateY(180deg);
}

/* Custom Scrollbar - Chỉ hiện trong modal */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  @apply bg-indigo-300/40 rounded-full border-2 border-solid border-white;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  @apply bg-indigo-400/60;
}

/* Ant Design Modal Fix: Nội dung cuộn, không làm page cuộn */
:deep(.flashcard-manager-fixed-modal .ant-modal) {
  max-height: 90vh;
  top: 5vh;
}

:deep(.flashcard-manager-fixed-modal .ant-modal-content) {
  height: 90vh;
  display: flex;
  flex-direction: column;
}

:deep(.flashcard-manager-fixed-modal .ant-modal-body) {
  flex: 1;
  overflow: hidden;
  padding: 0;
}
</style>