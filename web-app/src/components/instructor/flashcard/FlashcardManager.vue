<template>
  <a-modal
    v-model:open="innerOpen"
    :title="`🎴 Quản lý Flashcards — ${set?.title || ''}`"
    width="1400px"
    :footer="null"
    destroy-on-close
    class="flashcard-manager-modal"
    :class="{ 'preview-mode': previewMode }"
  >
    <div v-if="loading" class="py-24 flex flex-col items-center justify-center">
      <a-spin size="large" />
      <p class="text-gray-500 mt-4 text-lg">Đang tải flashcards...</p>
    </div>

    <div v-else>
      <!-- Modern Header -->
      <div class="relative overflow-hidden mb-8 p-6 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl text-white shadow-xl">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute inset-0 bg-white/5"></div>
        </div>
        
        <div class="relative flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
              <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div>
              <h3 class="text-2xl font-bold mb-1">
                Bộ thẻ học tập
              </h3>
              <div class="flex items-center space-x-4 text-white/90">
                <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                  <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V3a1 1 0 011 1v1H6V4a1 1 0 011-1V2z"/>
                  </svg>
                  {{ cards.length }} thẻ
                </span>
                <span class="text-sm">
                  💡 <kbd class="px-2 py-0.5 bg-white/20 rounded text-xs">Ctrl+S</kbd> để lưu nhanh
                </span>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <a-button 
              :type="previewMode ? 'default' : 'text'"
              size="large"
              @click="previewMode = !previewMode"
              :class="previewMode ? 'bg-white/20 border-white/30 text-white hover:bg-white/30' : 'text-white/80 hover:text-white hover:bg-white/10'"
              class="backdrop-blur-sm transition-all duration-200"
            >
              <component :is="previewMode ? 'EditOutlined' : 'EyeOutlined'" class="mr-2" />
              {{ previewMode ? 'Chế độ chỉnh sửa' : 'Xem trước' }}
            </a-button>
            
            <a-button 
              type="primary" 
              size="large" 
              @click="addNewCard" 
              class="bg-white text-indigo-600 border-0 hover:bg-gray-100 shadow-lg font-semibold px-6"
            >
              <PlusOutlined class="mr-2" /> Thêm Card
            </a-button>
          </div>
        </div>
      </div>

      <!-- Modern Empty State -->
      <div v-if="cards.length === 0" class="py-20 text-center">
        <div class="relative mb-8">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full opacity-50"></div>
          </div>
          <div class="relative">
            <svg class="mx-auto h-16 w-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
        </div>
        <div class="max-w-md mx-auto">
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Bắt đầu tạo flashcards</h3>
          <p class="text-gray-600 mb-8 leading-relaxed">
            Tạo những thẻ học tập tương tác để giúp học viên ghi nhớ kiến thức hiệu quả hơn
          </p>
          <a-button 
            type="primary" 
            size="large" 
            @click="addNewCard"
            class="px-8 py-3 h-auto text-lg font-semibold bg-gradient-to-r from-indigo-500 to-purple-600 border-0 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
          >
            <PlusOutlined class="mr-2" /> Tạo flashcard đầu tiên
          </a-button>
        </div>
      </div>

      <!-- Card List -->
      <div v-else class="space-y-6 max-h-[75vh] overflow-y-auto pr-2 modern-scrollbar">
        <!-- Modern Search & Filter -->
        <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-sm pb-6 border-b border-gray-100">
          <div class="flex items-center space-x-4">
            <div class="flex-1">
              <a-input-search
                v-model:value="searchQuery"
                placeholder="🔍 Tìm kiếm nội dung flashcard..."
                size="large"
                allow-clear
                class="modern-search"
              >
                <template #prefix>
                  <SearchOutlined class="text-gray-400" />
                </template>
              </a-input-search>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
              <span>Hiển thị:</span>
              <a-tag :color="searchQuery ? 'blue' : 'default'">
                {{ filteredCards.length }}/{{ cards.length }}
              </a-tag>
            </div>
          </div>
        </div>

        <TransitionGroup name="card-list" tag="div" class="space-y-4">
          <div
            v-for="(card, index) in filteredCards"
            :key="card.local_id"
            class="group relative"
          >
            <!-- Modern Preview Mode -->
            <div v-if="previewMode" class="flashcard-preview-modern group cursor-pointer" @click="flipCard(card)">
              <div class="flashcard-inner-modern" :class="{ 'flipped': card.isFlipped }">
                <!-- Front Side -->
                <div class="flashcard-side flashcard-front-modern">
                  <div class="flashcard-corner-indicator">{{ index + 1 }}</div>
                  <div class="flashcard-content-wrapper">
                    <div class="flashcard-label">
                      <span class="flashcard-label-icon">🎯</span>
                      <span class="flashcard-label-text">Câu hỏi</span>
                    </div>
                    <div class="flashcard-main-content">
                      <p class="flashcard-text">{{ card.front || 'Chưa có nội dung' }}</p>
                    </div>
                    <div v-if="card.image_url" class="flashcard-media">
                      <img :src="card.image_url" class="flashcard-image" alt="Hình ảnh flashcard" />
                    </div>
                    <div class="flashcard-hint">
                      <span class="flashcard-hint-text">Click để xem đáp án</span>
                    </div>
                  </div>
                </div>
                
                <!-- Back Side -->
                <div class="flashcard-side flashcard-back-modern">
                  <div class="flashcard-corner-indicator bg-emerald-500">{{ index + 1 }}</div>
                  <div class="flashcard-content-wrapper">
                    <div class="flashcard-label">
                      <span class="flashcard-label-icon">✨</span>
                      <span class="flashcard-label-text">Đáp án</span>
                    </div>
                    <div class="flashcard-main-content">
                      <p class="flashcard-text">{{ card.back || 'Chưa có nội dung' }}</p>
                    </div>
                    <div v-if="card.audio_url" class="flashcard-media">
                      <div class="audio-player-mini">
                        <audio controls class="w-full">
                          <source :src="card.audio_url" />
                        </audio>
                      </div>
                    </div>
                    <div class="flashcard-hint">
                      <span class="flashcard-hint-text">Click để quay lại</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modern Edit Mode -->
            <div v-else class="card-edit-modern">
              <div class="card-edit-header">
                <div class="flex items-center space-x-4">
                  <div class="card-number-modern" :class="card.id ? 'saved' : 'unsaved'">
                    <span class="card-number-text">{{ index + 1 }}</span>
                    <div class="card-status-indicator" :class="card.id ? 'saved' : 'unsaved'">
                      <component :is="card.id ? 'CheckOutlined' : 'ClockCircleOutlined'" class="w-3 h-3" />
                    </div>
                  </div>
                  <div class="flex-1">
                    <h4 class="card-title">Flashcard #{{ index + 1 }}</h4>
                    <div class="card-meta">
                      <span class="status-badge" :class="card.id ? 'saved' : 'draft'">
                        {{ card.id ? '✓ Đã lưu' : '⏳ Bản nháp' }}
                      </span>
                      <span class="shortcut-hint">
                        <kbd>Ctrl</kbd> + <kbd>S</kbd> để lưu nhanh
                      </span>
                    </div>
                  </div>
                </div>
                
                <div class="card-actions-modern">
                  <a-tooltip title="Xem trước flashcard">
                    <a-button 
                      type="text" 
                      size="large"
                      @click="quickPreview(card)"
                      class="action-btn preview-btn"
                    >
                      <EyeOutlined />
                    </a-button>
                  </a-tooltip>
                  
                  <a-button
                    type="primary"
                    size="large"
                    @click="saveCard(card)"
                    :loading="card.saving"
                    :disabled="!card.front?.trim() || !card.back?.trim()"
                    class="save-btn-modern"
                  >
                    <SaveOutlined class="mr-2" />
                    {{ card.saving ? 'Đang lưu...' : 'Lưu' }}
                  </a-button>

                  <a-popconfirm
                    v-if="card.id"
                    title="🗑️ Xóa card này?"
                    description="Hành động này không thể hoàn tác"
                    ok-text="Xóa"
                    cancel-text="Hủy"
                    ok-type="danger"
                    @confirm="deleteCard(card)"
                  >
                    <a-button danger size="large" class="delete-btn-modern">
                      <DeleteOutlined />
                    </a-button>
                  </a-popconfirm>

                  <a-button
                    v-else
                    danger
                    size="large"
                    @click="removeUnsavedCard(card)"
                    class="delete-btn-modern"
                  >
                    <DeleteOutlined />
                  </a-button>
                </div>
              </div>

              <div class="card-content-modern">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                  <!-- Front Side -->
                  <div class="content-section">
                    <div class="section-header">
                      <div class="section-icon front">🎯</div>
                      <div class="section-title">
                        Mặt trước <span class="required-indicator">*</span>
                      </div>
                      <div class="section-subtitle">Câu hỏi hoặc thuật ngữ</div>
                    </div>
                    <a-textarea
                      v-model:value="card.front"
                      placeholder="VD: What is Vue.js?"
                      :rows="4"
                      show-count
                      :maxlength="500"
                      @keydown="handleKeyDown($event, card)"
                      class="content-textarea"
                      :class="{ 'error': !card.front?.trim() }"
                    />

                    <div v-if="card.image_url" class="media-preview">
                      <div class="media-header">
                        <PictureOutlined class="media-icon" />
                        <span class="media-label">Hình ảnh đính kèm</span>
                        <a-button 
                          type="text" 
                          size="small"
                          @click="card.image_url = ''"
                          class="remove-media-btn"
                        >
                          <CloseOutlined />
                        </a-button>
                      </div>
                      <div class="media-content">
                        <img 
                          :src="card.image_url" 
                          class="preview-image"
                          @error="card.image_url = ''"
                          alt="Hình ảnh flashcard"
                        />
                      </div>
                    </div>
                  </div>

                  <!-- Back Side -->
                  <div class="content-section">
                    <div class="section-header">
                      <div class="section-icon back">✨</div>
                      <div class="section-title">
                        Mặt sau <span class="required-indicator">*</span>
                      </div>
                      <div class="section-subtitle">Đáp án hoặc định nghĩa</div>
                    </div>
                    <a-textarea
                      v-model:value="card.back"
                      placeholder="VD: A progressive JavaScript framework for building user interfaces..."
                      :rows="4"
                      show-count
                      :maxlength="500"
                      @keydown="handleKeyDown($event, card)"
                      class="content-textarea"
                      :class="{ 'error': !card.back?.trim() }"
                    />

                    <div v-if="card.audio_url" class="media-preview">
                      <div class="media-header">
                        <SoundOutlined class="media-icon" />
                        <span class="media-label">Audio đính kèm</span>
                        <a-button 
                          type="text" 
                          size="small"
                          @click="card.audio_url = ''"
                          class="remove-media-btn"
                        >
                          <CloseOutlined />
                        </a-button>
                      </div>
                      <div class="media-content">
                        <audio controls class="w-full audio-player">
                          <source :src="card.audio_url" />
                        </audio>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Media URLs Section -->
                <div class="media-urls-section">
                  <div class="media-urls-header">
                    <h4 class="media-urls-title">📎 Media đính kèm</h4>
                    <p class="media-urls-subtitle">Thêm hình ảnh hoặc audio để làm cho flashcard sinh động hơn</p>
                  </div>
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="media-input-group">
                      <label class="media-input-label">
                        <PictureOutlined class="media-input-icon" />
                        URL Hình ảnh
                      </label>
                      <a-input
                        v-model:value="card.image_url"
                        placeholder="https://example.com/image.jpg"
                        size="large"
                        class="media-input"
                      />
                    </div>

                    <div class="media-input-group">
                      <label class="media-input-label">
                        <SoundOutlined class="media-input-icon" />
                        URL Audio
                      </label>
                      <a-input
                        v-model:value="card.audio_url"
                        placeholder="https://example.com/audio.mp3"
                        size="large"
                        class="media-input"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TransitionGroup>
      </div>
    </div>

    <!-- Quick Preview Modal -->
    <a-modal
      v-model:open="quickPreviewModal.open"
      title="Xem trước Flashcard"
      :footer="null"
      width="500px"
    >
      <div v-if="quickPreviewModal.card" class="flashcard-preview-large" @click="flipCard(quickPreviewModal.card)">
        <div class="flashcard-inner-large" :class="{ 'flipped': quickPreviewModal.card.isFlipped }">
          <div class="flashcard-front-large">
            <div class="h-full flex flex-col">
              <div class="text-sm font-medium text-blue-600 mb-4">MẶT TRƯỚC</div>
              <div class="flex-1 flex items-center justify-center">
                <p class="text-center text-lg text-gray-800">{{ quickPreviewModal.card.front || 'Chưa có nội dung' }}</p>
              </div>
              <div v-if="quickPreviewModal.card.image_url" class="mt-4">
                <img :src="quickPreviewModal.card.image_url" class="w-full h-32 object-cover rounded-lg" />
              </div>
            </div>
          </div>
          <div class="flashcard-back-large">
            <div class="h-full flex flex-col">
              <div class="text-sm font-medium text-green-600 mb-4">MẶT SAU</div>
              <div class="flex-1 flex items-center justify-center">
                <p class="text-center text-lg text-gray-800">{{ quickPreviewModal.card.back || 'Chưa có nội dung' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="text-center text-gray-500 mt-4 text-sm">Click để lật thẻ</p>
    </a-modal>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { notification } from 'ant-design-vue'
import { 
  PlusOutlined, 
  SaveOutlined, 
  DeleteOutlined, 
  EyeOutlined,
  EditOutlined,
  SearchOutlined,
  CloseOutlined,
  PictureOutlined,
  SoundOutlined,
  CheckOutlined,
  ClockCircleOutlined
} from '@ant-design/icons-vue'
import { flashcardApi } from '@/api/instructor/flashcardApi'
import type { Flashcard } from '@/types/Flashcard'

const props = defineProps<{
  open: boolean
  set: any | null
}>()

const emit = defineEmits(['update:open', 'updated'])

const innerOpen = ref(props.open)
watch(() => props.open, v => innerOpen.value = v)
watch(innerOpen, v => emit('update:open', v))

const set = ref(props.set)

watch(() => props.set, val => {
  set.value = val
  if (val) loadCards()
})

// State
const loading = ref(false)
const cards = ref<any[]>([])
const previewMode = ref(false)
const searchQuery = ref('')

// Quick preview modal
const quickPreviewModal = ref({
  open: false,
  card: null as any
})

/* Temporary ID for unsaved cards */
let tempId = 1

// Computed
const filteredCards = computed(() => {
  if (!searchQuery.value.trim()) return cards.value
  
  const query = searchQuery.value.toLowerCase().trim()
  return cards.value.filter(card => 
    card.front?.toLowerCase().includes(query) ||
    card.back?.toLowerCase().includes(query)
  )
})

/* Load cards */
const loadCards = async () => {
  if (!set.value?.id) return
  loading.value = true
  try {
    const res = await flashcardApi.getBySet(set.value.id)
    cards.value = res.map((c: Flashcard) => ({
      ...c,
      local_id: c.id,
      saving: false,
      isFlipped: false
    }))
  } finally {
    loading.value = false
  }
}

/* Add new local card */
const addNewCard = () => {
  cards.value.unshift({
    id: null,
    local_id: `temp-${tempId++}`,
    flashcard_set_id: set.value.id,
    front: "",
    back: "",
    image_url: "",
    audio_url: "",
    saving: false,
    isFlipped: false
  })
}

/* Flip card animation */
const flipCard = (card: any) => {
  card.isFlipped = !card.isFlipped
}

/* Quick preview */
const quickPreview = (card: any) => {
  quickPreviewModal.value = {
    open: true,
    card: { ...card, isFlipped: false }
  }
}

/* Handle keyboard shortcuts */
const handleKeyDown = (event: KeyboardEvent, card: any) => {
  if (event.ctrlKey && event.key === 's') {
    event.preventDefault()
    saveCard(card)
  }
}

/* Save card (create or update) */
const saveCard = async (card: any) => {
  if (!set.value?.id) return

  if (!card.front?.trim() || !card.back?.trim()) {
    notification.error({ message: "Mặt trước và mặt sau không được để trống" })
    return
  }

  card.saving = true

  try {
    let res
    if (card.id) {
      res = await flashcardApi.update(card.id, {
        front: card.front.trim(),
        back: card.back.trim(),
        image_url: card.image_url?.trim() || null,
        audio_url: card.audio_url?.trim() || null,
      })
      notification.success({ message: "Đã cập nhật flashcard" })
    } else {
      res = await flashcardApi.create(set.value.id, {
        front: card.front.trim(),
        back: card.back.trim(),
        image_url: card.image_url?.trim() || null,
        audio_url: card.audio_url?.trim() || null,
      })
      notification.success({ message: "Đã tạo flashcard mới" })
    }

    // Replace local card with saved version
    Object.assign(card, res)
    card.local_id = res.id
    card.isFlipped = false
    emit('updated')
  } catch (e: any) {
    notification.error({ message: e?.message || "Lưu thất bại" })
  } finally {
    card.saving = false
  }
}

/* Delete card */
const deleteCard = async (card: any) => {
  try {
    await flashcardApi.delete(card.id)
    notification.success({ message: "Đã xóa flashcard" })
    cards.value = cards.value.filter(c => c.local_id !== card.local_id)
    emit('updated')
  } catch {
    notification.error({ message: "Xóa thất bại" })
  }
}

/* Remove unsaved card */
const removeUnsavedCard = (card: any) => {
  cards.value = cards.value.filter(c => c.local_id !== card.local_id)
}
</script>

<style scoped>
/* Author: Truong */

/* Modern Scrollbar */
.modern-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.modern-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.modern-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.02);
  border-radius: 10px;
}

.modern-scrollbar::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, rgba(99, 102, 241, 0.3), rgba(168, 85, 247, 0.3));
  border-radius: 10px;
  border: 2px solid transparent;
  background-clip: padding-box;
}

.modern-scrollbar::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, rgba(99, 102, 241, 0.5), rgba(168, 85, 247, 0.5));
  background-clip: padding-box;
}

/* Modern Search */
:deep(.modern-search .ant-input-affix-wrapper) {
  border-radius: 16px;
  border: 2px solid transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #f3f4f6, #e5e7eb);
  background-clip: padding-box, border-box;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.modern-search .ant-input-affix-wrapper:focus),
:deep(.modern-search .ant-input-affix-wrapper-focused) {
  border-color: transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #6366f1, #a855f7);
  background-clip: padding-box, border-box;
  box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1);
}

/* Modern Flashcard Preview */
.flashcard-preview-modern {
  perspective: 1000px;
  height: 280px;
  margin-bottom: 2rem;
  transition: transform 0.3s ease;
}

.flashcard-preview-modern:hover {
  transform: translateY(-4px);
}

.flashcard-inner-modern {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s cubic-bezier(0.23, 1, 0.320, 1);
  transform-style: preserve-3d;
}

.flashcard-inner-modern.flipped {
  transform: rotateY(180deg);
}

.flashcard-side {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 20px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.flashcard-front-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.flashcard-back-modern {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  transform: rotateY(180deg);
}

.flashcard-corner-indicator {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 32px;
  height: 32px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: 600;
  z-index: 10;
}

.flashcard-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 24px;
}

.flashcard-label {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}

.flashcard-label-icon {
  font-size: 18px;
  margin-right: 8px;
}

.flashcard-label-text {
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  opacity: 0.9;
}

.flashcard-main-content {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px 0;
}

.flashcard-text {
  font-size: 18px;
  font-weight: 500;
  line-height: 1.6;
  text-align: center;
  max-width: 100%;
  word-wrap: break-word;
}

.flashcard-media {
  margin-top: 16px;
}

.flashcard-image {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 12px;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.flashcard-hint {
  margin-top: auto;
  padding-top: 16px;
}

.flashcard-hint-text {
  font-size: 12px;
  opacity: 0.8;
  font-style: italic;
}

.audio-player-mini {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  padding: 12px;
}

/* Modern Edit Card */
.card-edit-modern {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border: 1px solid rgba(148, 163, 184, 0.1);
  border-radius: 24px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.card-edit-modern::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
}

.card-edit-modern:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  transform: translateY(-2px);
}

.card-edit-header {
  display: flex;
  align-items: center;
  justify-content: between;
  padding-bottom: 20px;
  margin-bottom: 24px;
  border-bottom: 1px solid rgba(148, 163, 184, 0.1);
}

.card-number-modern {
  position: relative;
  width: 60px;
  height: 60px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 18px;
  transition: all 0.3s ease;
}

.card-number-modern.saved {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
}

.card-number-modern.unsaved {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
}

.card-status-indicator {
  position: absolute;
  top: -6px;
  right: -6px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
}

.card-status-indicator.saved {
  background: #10b981;
  color: white;
}

.card-status-indicator.unsaved {
  background: #f59e0b;
  color: white;
}

.card-title {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 4px;
}

.card-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 14px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-weight: 500;
  font-size: 12px;
}

.status-badge.saved {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.draft {
  background: #fef3c7;
  color: #92400e;
}

.shortcut-hint {
  color: #6b7280;
  font-size: 12px;
}

.shortcut-hint kbd {
  background: #f3f4f6;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  padding: 2px 6px;
  font-size: 10px;
  font-family: monospace;
}

.card-actions-modern {
  display: flex;
  align-items: center;
  gap: 12px;
}

.action-btn {
  border-radius: 12px;
  border: 1px solid rgba(148, 163, 184, 0.2);
  transition: all 0.2s ease;
}

.action-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.preview-btn:hover {
  background: linear-gradient(135deg, #ede9fe, #ddd6fe);
  border-color: #a855f7;
  color: #7c3aed;
}

.save-btn-modern {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  border: none;
  border-radius: 12px;
  font-weight: 600;
  box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);
}

.save-btn-modern:hover {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  transform: translateY(-1px);
  box-shadow: 0 6px 8px -1px rgba(59, 130, 246, 0.6);
}

.save-btn-modern:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.delete-btn-modern {
  border-radius: 12px;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.delete-btn-modern:hover {
  background: linear-gradient(135deg, #fef2f2, #fee2e2);
  border-color: #ef4444;
  transform: translateY(-1px);
}

/* Content Sections */
.card-content-modern {
  margin-top: 8px;
}

.content-section {
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(148, 163, 184, 0.1);
  border-radius: 20px;
  padding: 24px;
  transition: all 0.3s ease;
}

.content-section:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: rgba(99, 102, 241, 0.2);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.section-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  font-weight: 600;
}

.section-icon.front {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.section-icon.back {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
}

.section-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
}

.section-subtitle {
  color: #6b7280;
  font-size: 14px;
  margin-left: auto;
  font-style: italic;
}

.required-indicator {
  color: #ef4444;
  font-weight: 700;
}

.content-textarea {
  border-radius: 16px;
  border: 2px solid transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #f1f5f9, #e2e8f0);
  background-clip: padding-box, border-box;
  transition: all 0.3s ease;
  font-size: 16px;
  line-height: 1.6;
}

.content-textarea:focus {
  border-color: transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #6366f1, #a855f7);
  background-clip: padding-box, border-box;
  box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);
}

.content-textarea.error {
  background: linear-gradient(white, white), linear-gradient(45deg, #fee2e2, #fecaca);
  background-clip: padding-box, border-box;
}

/* Media Preview */
.media-preview {
  margin-top: 20px;
  background: rgba(249, 250, 251, 0.8);
  border: 1px solid rgba(229, 231, 235, 0.5);
  border-radius: 16px;
  overflow: hidden;
}

.media-header {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: rgba(243, 244, 246, 0.5);
  border-bottom: 1px solid rgba(229, 231, 235, 0.5);
}

.media-icon {
  color: #6366f1;
}

.media-label {
  flex: 1;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
}

.remove-media-btn {
  color: #6b7280;
  border-radius: 8px;
}

.remove-media-btn:hover {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.media-content {
  padding: 16px;
}

.preview-image {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 12px;
  border: 1px solid rgba(229, 231, 235, 0.5);
}

.audio-player {
  border-radius: 8px;
}

/* Media URLs Section */
.media-urls-section {
  background: linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9));
  border: 1px solid rgba(148, 163, 184, 0.1);
  border-radius: 20px;
  padding: 24px;
}

.media-urls-header {
  text-align: center;
  margin-bottom: 24px;
}

.media-urls-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 4px;
}

.media-urls-subtitle {
  color: #6b7280;
  font-size: 14px;
}

.media-input-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.media-input-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
}

.media-input-icon {
  color: #6366f1;
}

:deep(.media-input .ant-input-affix-wrapper) {
  border-radius: 12px;
  border: 2px solid rgba(148, 163, 184, 0.2);
  transition: all 0.3s ease;
}

:deep(.media-input .ant-input-affix-wrapper:focus),
:deep(.media-input .ant-input-affix-wrapper-focused) {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Card List Animation */
.card-list-enter-active {
  transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
}

.card-list-leave-active {
  transition: all 0.3s ease-out;
}

.card-list-enter-from {
  opacity: 0;
  transform: translateY(30px) scale(0.95);
}

.card-list-leave-to {
  opacity: 0;
  transform: translateX(-30px) scale(0.95);
}

.card-list-move {
  transition: transform 0.5s cubic-bezier(0.23, 1, 0.320, 1);
}

/* Quick Preview Modal Enhancement */
:deep(.flashcard-preview-large) {
  perspective: 1000px;
  height: 320px;
  cursor: pointer;
}

:deep(.flashcard-inner-large) {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s cubic-bezier(0.23, 1, 0.320, 1);
  transform-style: preserve-3d;
}

:deep(.flashcard-inner-large.flipped) {
  transform: rotateY(180deg);
}

:deep(.flashcard-front-large),
:deep(.flashcard-back-large) {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 20px;
  padding: 32px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

:deep(.flashcard-front-large) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

:deep(.flashcard-back-large) {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  transform: rotateY(180deg);
}

/* Responsive Design */
@media (max-width: 1280px) {
  .flashcard-manager-modal {
    width: 95vw !important;
    max-width: 1000px !important;
  }
  
  .card-edit-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }
  
  .card-actions-modern {
    width: 100%;
    justify-content: flex-end;
  }
}

@media (max-width: 768px) {
  .content-section {
    padding: 16px;
  }
  
  .card-edit-modern {
    padding: 16px;
  }
  
  .flashcard-preview-modern {
    height: 240px;
  }
  
  .flashcard-text {
    font-size: 16px;
  }
}
</style>
