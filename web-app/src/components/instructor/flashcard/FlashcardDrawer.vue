<template>
  <a-drawer
    v-model:open="innerOpen"
    :title="drawerTitle"
    width="720"
    destroy-on-close
    class="flashcard-drawer"
  >
    <div v-if="!topic" class="h-full flex items-center justify-center text-gray-400">
      <div class="text-center space-y-2">
        <p class="text-lg font-medium">Select a topic to manage flashcards</p>
        <p class="text-sm">Click on a topic in the table to open its flashcards.</p>
      </div>
    </div>

    <div v-else class="space-y-4">
      <!-- Header info -->
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500 uppercase tracking-wide">Topic</p>
          <h3 class="text-xl font-semibold text-gray-900">
            {{ topic.title }}
          </h3>
          <p class="text-xs text-gray-400 mt-1">
            {{ flashcards.length }} flashcard(s)
          </p>
        </div>

        <a-button type="primary" @click="openCreateModal">
          <template #icon>
            <PlusOutlined />
          </template>
          Add Flashcard
        </a-button>
      </div>

      <!-- Content -->
      <div class="border-t border-gray-100 pt-4">
        <div v-if="loading" class="flex items-center justify-center py-10">
          <a-spin tip="Loading flashcards..." />
        </div>

        <div v-else-if="flashcards.length === 0" class="py-10">
          <a-empty
            description="No flashcards yet. Create the first one for this topic."
          >
            <a-button type="dashed" @click="openCreateModal">
              <template #icon><PlusOutlined /></template>
              Create flashcard
            </a-button>
          </a-empty>
        </div>

        <div v-else class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
          <div
            v-for="(card, index) in flashcards"
            :key="card.id"
            class="border border-gray-100 rounded-xl p-4 bg-white hover:shadow-sm transition-shadow flex gap-4"
          >
            <!-- Index badge -->
            <div
              class="w-8 h-8 flex items-center justify-center rounded-full bg-teal-50 text-teal-600 text-sm font-semibold flex-shrink-0"
            >
              {{ index + 1 }}
            </div>

            <!-- Card content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                  <p class="text-[11px] font-semibold uppercase text-gray-400 mb-1">
                    Front
                  </p>
                  <p class="text-gray-900 font-medium truncate">
                    {{ card.front }}
                  </p>

                  <p class="text-[11px] font-semibold uppercase text-gray-400 mt-3 mb-1">
                    Back
                  </p>
                  <p class="text-gray-700 text-sm whitespace-pre-line line-clamp-3">
                    {{ card.back }}
                  </p>

                  <div class="flex items-center gap-2 mt-2">
                    <span
                      v-if="card.image_url"
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] bg-blue-50 text-blue-600"
                    >
                      <i class="fas fa-image mr-1"></i> Image attached
                    </span>
                    <span
                      v-if="card.audio_url"
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600"
                    >
                      <i class="fas fa-volume-up mr-1"></i> Audio attached
                    </span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                  <a-button type="link" size="small" @click="openEditModal(card)">
                    <template #icon><EditOutlined /></template>
                    Edit
                  </a-button>

                  <a-popconfirm
                    title="Delete this flashcard?"
                    ok-text="Delete"
                    cancel-text="Cancel"
                    @confirm="handleDelete(card)"
                  >
                    <a-button type="link" danger size="small">
                      <template #icon><DeleteOutlined /></template>
                      Delete
                    </a-button>
                  </a-popconfirm>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Create / Edit Flashcard -->
    <a-modal
      v-model:open="modal.open"
      :title="modal.mode === 'create' ? 'Create Flashcard' : 'Edit Flashcard'"
      :confirm-loading="modal.saving"
      @ok="handleSubmit"
      destroy-on-close
    >
      <a-form layout="vertical">
        <a-form-item
          label="Front (Question / Term)"
          name="front"
          :rules="[{ required: true, message: 'Please input front text' }]"
        >
          <a-input
            v-model:value="form.front"
            placeholder="E.g. What is a closure in JavaScript?"
          />
        </a-form-item>

        <a-form-item
          label="Back (Answer / Definition)"
          name="back"
          :rules="[{ required: true, message: 'Please input back text' }]"
        >
          <a-textarea
            v-model:value="form.back"
            :rows="4"
            placeholder="Explain the concept in your own words..."
          />
        </a-form-item>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a-form-item label="Image URL (optional)" name="image_url">
            <a-input
              v-model:value="form.image_url"
              placeholder="https://..."
            />
          </a-form-item>

          <a-form-item label="Audio URL (optional)" name="audio_url">
            <a-input
              v-model:value="form.audio_url"
              placeholder="https://..."
            />
          </a-form-item>
        </div>
      </a-form>
    </a-modal>
  </a-drawer>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { notification } from 'ant-design-vue'
import { PlusOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'
import { flashcardApi } from '@/api/instructor/flashcardApi'
import type { Flashcard, FlashcardPayload } from '@/types/Flashcard'

interface TopicLike {
  id: number
  title: string
  [key: string]: any
}

const props = defineProps<{
  open: boolean
  topic: TopicLike | null
}>()

const emit = defineEmits<{
  'update:open': [boolean]
  updated: []
}>()

const innerOpen = ref(props.open)

watch(
  () => props.open,
  (val) => {
    innerOpen.value = val
    if (val && props.topic?.id) {
      loadFlashcards()
    }
  }
)

watch(innerOpen, (val) => {
  emit('update:open', val)
})

const topic = computed(() => props.topic)

const drawerTitle = computed(() =>
  topic.value ? `Flashcards for Topic: ${topic.value.title}` : 'Flashcards'
)

const flashcards = ref<Flashcard[]>([])
const loading = ref(false)

const modal = ref<{
  open: boolean
  mode: 'create' | 'edit'
  saving: boolean
  editingId: number | null
}>({
  open: false,
  mode: 'create',
  saving: false,
  editingId: null,
})

const form = ref<FlashcardPayload>({
  front: '',
  back: '',
  image_url: '',
  audio_url: '',
})

const resetForm = () => {
  form.value = {
    front: '',
    back: '',
    image_url: '',
    audio_url: '',
  }
  modal.value.editingId = null
}

const openCreateModal = () => {
  modal.value.mode = 'create'
  resetForm()
  modal.value.open = true
}

const openEditModal = (card: Flashcard) => {
  modal.value.mode = 'edit'
  modal.value.editingId = card.id
  form.value = {
    front: card.front,
    back: card.back,
    image_url: card.image_url || '',
    audio_url: card.audio_url || '',
  }
  modal.value.open = true
}

const loadFlashcards = async () => {
  if (!topic.value?.id) return
  loading.value = true
  try {
    const data = await flashcardApi.getFlashcards(topic.value.id)
    flashcards.value = data
  } catch (error: any) {
    console.error(error)
    notification.error({
      message: 'Failed to load flashcards',
      description: error?.message || 'Please try again later.',
    })
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  if (!topic.value?.id) {
    notification.error({ message: 'Missing topic id' })
    return
  }

  modal.value.saving = true
  try {
    if (modal.value.mode === 'create') {
      await flashcardApi.createFlashcard(topic.value.id, form.value)
      notification.success({ message: 'Flashcard created successfully' })
    } else if (modal.value.mode === 'edit' && modal.value.editingId) {
      await flashcardApi.updateFlashcard(modal.value.editingId, form.value)
      notification.success({ message: 'Flashcard updated successfully' })
    }

    modal.value.open = false
    await loadFlashcards()
    emit('updated')
  } catch (error: any) {
    console.error(error)
    notification.error({
      message: 'Save failed',
      description: error?.message || 'Please check your input and try again.',
    })
  } finally {
    modal.value.saving = false
  }
}

const handleDelete = async (card: Flashcard) => {
  try {
    await flashcardApi.deleteFlashcard(card.id)
    notification.success({ message: 'Flashcard deleted' })
    await loadFlashcards()
    emit('updated')
  } catch (error: any) {
    console.error(error)
    notification.error({
      message: 'Delete failed',
      description: error?.message || 'Please try again.',
    })
  }
}
</script>

<style scoped>
.flashcard-drawer :deep(.ant-drawer-body) {
  padding-top: 16px;
}
</style>
