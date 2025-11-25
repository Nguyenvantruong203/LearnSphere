<template>
  <a-drawer v-model:open="innerOpen" :title="topic ? `Flashcard Sets - ${topic.title}` : 'Flashcard Sets'" width="900"
    destroy-on-close class="flashcard-sets-drawer">
    <!-- No Topic Selected -->
    <div v-if="!topic" class="flex h-full items-center justify-center px-6">
      <div class="text-center max-w-md">
        <div class="relative mb-8">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 opacity-50"></div>
          </div>
          <svg class="relative mx-auto h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Select a Topic First</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Please select a topic to manage its flashcard sets
        </p>
      </div>
    </div>

    <!-- Topic Selected -->
    <div v-else class="flex h-full flex-col">
      <!-- Header -->
      <div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
              <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium opacity-90">Topic</p>
              <h2 class="text-xl font-bold">{{ topic.title }}</h2>
              <div class="mt-2 flex items-center gap-4 text-sm">
                <span class="rounded-full bg-white/20 px-3 py-1 font-medium">
                  {{ sets.length }} set{{ sets.length !== 1 ? 's' : '' }}
                </span>
                <span class="opacity-90">Organize flashcards into sets</span>
              </div>
            </div>
          </div>
          <a-button type="primary" size="large" @click="openCreateSetModal"
            class="flex items-center border-0 bg-white font-semibold text-emerald-600 shadow-lg hover:bg-gray-50">
            <PlusOutlined class="mr-2" /> Create Set
          </a-button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-1 items-center justify-center">
        <div class="text-center">
          <a-spin size="large" />
          <p class="mt-4 text-base text-gray-500">Loading sets...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="sets.length === 0" class="flex flex-1 items-center justify-center px-8">
        <div class="text-center max-w-md">
          <div class="relative mb-8">
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="h-32 w-32 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 opacity-50"></div>
            </div>
            <svg class="relative mx-auto h-14 w-14 text-emerald-500" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <h3 class="mb-2 text-xl font-bold text-gray-900">Create Your First Flashcard Set</h3>
          <p class="mb-8 text-sm text-gray-600">
            Flashcard sets help you organize study cards by topic
          </p>
          <a-button type="primary" size="large" @click="openCreateSetModal"
            class="h-12 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 text-base font-semibold shadow-lg hover:shadow-xl">
            <PlusOutlined class="mr-2" /> Create First Set
          </a-button>
        </div>
      </div>

      <!-- Sets List -->
      <div v-else class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="space-y-5">
          <div v-for="set in sets" :key="set.id"
            class="overflow-hidden rounded-2xl border bg-white shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5"
            :class="{ 'ring-2 ring-emerald-400': false }">
            <div class="border-b border-gray-100 p-5">
              <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                  <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900">{{ set.title }}</h3>
                    <div class="mt-1 flex items-center gap-3 text-xs text-gray-500">
                      <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-emerald-700">
                        {{ set.flashcards_count || 0 }} card{{ set.flashcards_count !== 1 ? 's' : '' }}
                      </span>
                      <span>Recently updated</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1">
                  <a-tooltip title="Manage Cards">
                    <a-button type="text" size="large" @click="openFlashcardManager(set)"
                      class="text-gray-600 hover:bg-blue-50">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                      </svg>
                    </a-button>
                  </a-tooltip>

                  <a-tooltip title="Edit Set">
                    <a-button type="text" size="large" @click="openEditSetModal(set)"
                      class="text-gray-600 hover:bg-amber-50">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a-button>
                  </a-tooltip>

                  <a-popconfirm title="Delete this set?"
                    description="All flashcards in this set will be permanently deleted" ok-text="Delete"
                    cancel-text="Cancel" ok-type="danger" @confirm="handleDeleteSet(set)">
                    <a-tooltip title="Delete Set">
                      <a-button danger type="text" size="large" class="text-gray-600 hover:bg-red-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </a-button>
                    </a-tooltip>
                  </a-popconfirm>
                </div>
              </div>
            </div>

            <div class="p-5">
              <p class="mb-4 text-sm text-gray-600">
                {{ set.description || 'No description added yet.' }}
              </p>
              <div class="flex justify-end">
                <a-button type="primary" @click="openFlashcardManager(set)"
                  class="flex items-center rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 font-medium shadow hover:shadow-md">
                  <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                  </svg>
                  Manage Cards
                </a-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <a-modal v-model:open="modalSet.open"
      :title="modalSet.mode === 'create' ? 'Create New Flashcard Set' : 'Edit Flashcard Set'"
      :confirm-loading="modalSet.saving" width="600px" destroy-on-close wrap-class-name="flashcard-set-modal-fixed"
      :mask-closable="false" :get-container="getModalContainer" @ok="submitSet">
      <a-form layout="vertical" class="space-y-5 py-4">
        <a-form-item label="Set Name" :rules="[{ required: true, message: 'Please enter a set name' }]">
          <a-input v-model:value="setForm.title" placeholder="e.g., Chapter 1 Vocabulary, Math Formulas..." size="large"
            class="rounded-lg border-2 border-gray-200 focus:border-emerald-500" />
        </a-form-item>

        <a-form-item label="Description (optional)">
          <a-textarea v-model:value="setForm.description" :rows="4"
            placeholder="Brief description of this flashcard set..." show-count :maxlength="200"
            class="rounded-lg border-2 border-gray-200 focus:border-emerald-500" />
        </a-form-item>
      </a-form>
    </a-modal>

    <!-- Flashcard Manager -->
    <FlashcardManager v-model:open="manager.open" :set="manager.set" @updated="loadSets()" />
  </a-drawer>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { notification } from 'ant-design-vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import FlashcardManager from './FlashcardManager.vue'
import { flashcardSetApi } from '@/api/instructor/flashcardSetApi'

const props = defineProps<{ open: boolean; topic: any | null }>()
const emit = defineEmits(['update:open', 'updated'])

const innerOpen = ref(props.open)
watch(() => props.open, v => innerOpen.value = v)
watch(innerOpen, v => emit('update:open', v))

const topic = computed(() => props.topic)
const sets = ref<any[]>([])
const loading = ref(false)

const modalSet = ref({
  open: false,
  mode: "create" as "create" | "edit",
  saving: false,
  editingId: null as number | null,
})

const setForm = ref({
  title: "",
  description: "",
})

const manager = ref({
  open: false,
  set: null as any | null,
})
const getModalContainer = () => document.body

const loadSets = async () => {
  if (!topic.value?.id) return
  loading.value = true
  try {
    const res = await flashcardSetApi.getSets(topic.value.id)
    sets.value = res
  } finally {
    loading.value = false
  }
}

const openCreateSetModal = () => {
  modalSet.value = { open: true, mode: "create", saving: false, editingId: null }
  setForm.value = { title: "", description: "" }
}

const openEditSetModal = (set: any) => {
  modalSet.value = { open: true, mode: "edit", saving: false, editingId: set.id }
  setForm.value = { title: set.title, description: set.description || "" }
}

const submitSet = async () => {
  modalSet.value.saving = true
  try {
    if (modalSet.value.mode === "create") {
      await flashcardSetApi.createSet(topic.value.id, setForm.value)
      notification.success({ message: "Set created successfully" })
    } else {
      await flashcardSetApi.updateSet(modalSet.value.editingId!, setForm.value)
      notification.success({ message: "Set updated successfully" })
    }
    modalSet.value.open = false
    loadSets()
    emit("updated")
  } catch (e: any) {
    notification.error({ message: e?.message || "Operation failed" })
  } finally {
    modalSet.value.saving = false
  }
}

const handleDeleteSet = async (set: any) => {
  try {
    await flashcardSetApi.deleteSet(set.id)
    notification.success({ message: "Set deleted" })
    loadSets()
  } catch {
    notification.error({ message: "Delete failed" })
  }
}

const openFlashcardManager = (set: any) => {
  manager.value = { open: true, set }
}

watch(innerOpen, val => {
  if (val && topic.value?.id) loadSets()
})
</script>

<style scoped>
/* Smooth scrollbar */
.modern-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(16, 185, 129, 0.3) transparent;
}

.modern-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.modern-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(16, 185, 129, 0.4);
  border-radius: 9999px;
}

.modern-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(16, 185, 129, 0.6);
}

:deep(.flashcard-set-modal-fixed .ant-modal) {
  width: 600px !important;
  max-width: 600px !important;
  margin: 0 auto;
}

:deep(.flashcard-set-modal-fixed .ant-modal-content) {
  border-radius: 16px;
  overflow: hidden;
}

/* Optional: đẹp hơn */
:deep(.flashcard-set-modal-fixed .ant-modal-header) {
  border-radius: 16px 16px 0 0;
  background: linear-gradient(to right, #10b981, #14b8a6);
  color: white;
  border-bottom: none;
}

:deep(.flashcard-set-modal-fixed .ant-modal-title) {
  color: white;
  font-weight: 600;
}

:deep(.flashcard-set-modal-fixed .ant-modal-close) {
  color: white;
  opacity: 0.8;
}

:deep(.flashcard-set-modal-fixed .ant-modal-close:hover) {
  opacity: 1;
  background: rgba(255, 255, 255, 0.2);
}
</style>