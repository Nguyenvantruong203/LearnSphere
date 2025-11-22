<template>
  <a-drawer
    v-model:open="innerOpen"
    :title="topic ? `🎴 ${topic.title} - Flashcard Sets` : '🎴 Flashcard Sets'"
    width="900"
    destroy-on-close
    class="flashcard-sets-drawer"
  >
    <!-- No Topic Selected -->
    <div v-if="!topic" class="h-full flex items-center justify-center">
      <div class="text-center max-w-md">
        <div class="relative mb-8">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full opacity-50"></div>
          </div>
          <div class="relative">
            <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Chọn Topic trước</h3>
        <p class="text-gray-600 leading-relaxed">
          Vui lòng chọn một topic để quản lý các bộ flashcard của nó
        </p>
      </div>
    </div>

    <!-- Topic Content -->
    <div v-else class="h-full flex flex-col">
      <!-- Modern Header -->
      <div class="relative overflow-hidden mb-6 p-6 bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 rounded-2xl text-white shadow-xl">
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
              <div class="text-sm font-medium text-white/80 mb-1">📚 Topic</div>
              <h2 class="text-2xl font-bold mb-1">{{ topic.title }}</h2>
              <div class="flex items-center space-x-4 text-white/90">
                <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                  <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                  {{ sets.length }} bộ thẻ
                </span>
                <span class="text-sm">
                  💡 Tạo bộ thẻ để tổ chức flashcards
                </span>
              </div>
            </div>
          </div>

          <a-button 
            type="primary" 
            size="large" 
            @click="openCreateSetModal"
            class="bg-white text-emerald-600 border-0 hover:bg-gray-100 shadow-lg font-semibold px-6"
          >
            <PlusOutlined class="mr-2" /> Tạo bộ thẻ
          </a-button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex-1 flex items-center justify-center">
        <div class="text-center">
          <a-spin size="large" />
          <p class="text-gray-500 mt-4 text-lg">Đang tải bộ thẻ...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="sets.length === 0" class="flex-1 flex items-center justify-center">
        <div class="text-center max-w-md">
          <div class="relative mb-8">
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-32 h-32 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full opacity-50"></div>
            </div>
            <div class="relative">
              <svg class="mx-auto h-16 w-16 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Tạo bộ flashcard đầu tiên</h3>
          <p class="text-gray-600 mb-8 leading-relaxed">
            Bộ flashcard giúp bạn tổ chức các thẻ học tập theo chủ đề cụ thể
          </p>
          <a-button 
            type="primary" 
            size="large" 
            @click="openCreateSetModal"
            class="px-8 py-3 h-auto text-lg font-semibold bg-gradient-to-r from-emerald-500 to-teal-600 border-0 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
          >
            <PlusOutlined class="mr-2" /> Tạo bộ đầu tiên
          </a-button>
        </div>
      </div>

      <!-- Flashcard Sets List -->
      <div v-else class="flex-1 overflow-hidden">
        <div class="h-full overflow-y-auto pr-2 modern-scrollbar">
          <div class="grid grid-cols-1 gap-6">
            <div
              v-for="set in sets"
              :key="set.id"
              class="flashcard-set-card group"
            >
              <!-- Card Header -->
              <div class="flex items-start justify-between p-6 border-b border-gray-100">
                <div class="flex-1">
                  <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                      </svg>
                    </div>
                    <div>
                      <h3 class="text-lg font-bold text-gray-900">{{ set.title }}</h3>
                      <div class="flex items-center space-x-3 mt-1">
                        <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium">
                          <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V3a1 1 0 011 1v1H6V4a1 1 0 011-1V2z"/>
                          </svg>
                          {{ set.flashcards_count || 0 }} thẻ
                        </span>
                        <span class="text-xs text-gray-500">
                          Cập nhật gần đây
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <a-tooltip title="Quản lý flashcards">
                    <a-button 
                      type="text" 
                      size="large"
                      @click="openFlashcardManager(set)"
                      class="action-btn manage-btn"
                    >
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                      </svg>
                    </a-button>
                  </a-tooltip>
                  
                  <a-tooltip title="Chỉnh sửa bộ thẻ">
                    <a-button 
                      type="text" 
                      size="large"
                      @click="openEditSetModal(set)"
                      class="action-btn edit-btn"
                    >
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a-button>
                  </a-tooltip>

                  <a-popconfirm
                    title="🗑️ Xóa bộ thẻ này?"
                    description="Tất cả flashcard trong bộ sẽ bị xóa vĩnh viễn"
                    ok-text="Xóa"
                    cancel-text="Hủy"
                    ok-type="danger"
                    @confirm="handleDeleteSet(set)"
                  >
                    <a-tooltip title="Xóa bộ thẻ">
                      <a-button 
                        danger 
                        type="text"
                        size="large"
                        class="action-btn delete-btn"
                      >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </a-button>
                    </a-tooltip>
                  </a-popconfirm>
                </div>
              </div>

              <!-- Card Content -->
              <div class="p-6">
                <div class="mb-4">
                  <p class="text-gray-600 leading-relaxed">
                    {{ set.description || 'Chưa có mô tả cho bộ flashcard này.' }}
                  </p>
                </div>

                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>
                      <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Tạo gần đây
                    </span>
                  </div>
                  
                  <a-button 
                    type="primary"
                    @click="openFlashcardManager(set)"
                    class="manage-cards-btn"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                    </svg>
                    Quản lý thẻ
                  </a-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modern Modal: Create / Edit Flashcard Set -->
    <a-modal
      v-model:open="modalSet.open"
      :title="modalSet.mode === 'create' ? '✨ Tạo bộ flashcard mới' : '📝 Chỉnh sửa bộ flashcard'"
      :confirm-loading="modalSet.saving"
      destroy-on-close
      width="600px"
      class="flashcard-set-modal"
      @ok="submitSet"
    >
      <div class="py-4">
        <a-form layout="vertical" class="space-y-6">
          <a-form-item 
            label="Tên bộ thẻ" 
            :rules="[{ required: true, message: 'Vui lòng nhập tên bộ thẻ' }]"
            class="form-item-modern"
          >
            <a-input 
              v-model:value="setForm.title" 
              placeholder="VD: Từ vựng chương 1, Công thức Toán học..."
              size="large"
              class="input-modern"
            >
              <template #prefix>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V3a1 1 0 011 1v1H6V4a1 1 0 011-1V2z"/>
                </svg>
              </template>
            </a-input>
          </a-form-item>

          <a-form-item label="Mô tả (tùy chọn)" class="form-item-modern">
            <a-textarea 
              v-model:value="setForm.description" 
              :rows="4" 
              placeholder="Mô tả ngắn về nội dung bộ flashcard này..."
              class="textarea-modern"
              show-count
              :maxlength="200"
            />
          </a-form-item>
        </a-form>
      </div>
    </a-modal>

    <!-- Flashcard Manager -->
    <FlashcardManager
      v-model:open="manager.open"
      :set="manager.set"
      @updated="loadSets()"
    />
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
  setForm.value = {
    title: set.title,
    description: set.description || "",
  }
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
    notification.error({ message: e?.message || "Failed" })
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
  manager.value = {
    open: true,
    set,
  }
}

watch(innerOpen, val => {
  if (val && topic.value?.id) loadSets()
})
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
  background: linear-gradient(45deg, rgba(16, 185, 129, 0.3), rgba(20, 184, 166, 0.3));
  border-radius: 10px;
  border: 2px solid transparent;
  background-clip: padding-box;
}

.modern-scrollbar::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, rgba(16, 185, 129, 0.5), rgba(20, 184, 166, 0.5));
  background-clip: padding-box;
}

/* Flashcard Set Card */
.flashcard-set-card {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border: 1px solid rgba(148, 163, 184, 0.1);
  border-radius: 20px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.flashcard-set-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #10b981, #14b8a6, #06b6d4);
}

.flashcard-set-card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  transform: translateY(-2px);
}

/* Action Buttons */
.action-btn {
  border-radius: 12px;
  border: 1px solid rgba(148, 163, 184, 0.2);
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
}

.action-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.manage-btn:hover {
  background: linear-gradient(135deg, #e0f2fe, #b3e5fc);
  border-color: #06b6d4;
  color: #0891b2;
}

.edit-btn:hover {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border-color: #f59e0b;
  color: #d97706;
}

.delete-btn:hover {
  background: linear-gradient(135deg, #fef2f2, #fee2e2);
  border-color: #ef4444;
  color: #dc2626;
}

/* Manage Cards Button */
.manage-cards-btn {
  background: linear-gradient(135deg, #10b981, #059669);
  border: none;
  border-radius: 12px;
  font-weight: 600;
  box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.5);
  transition: all 0.3s ease;
}

.manage-cards-btn:hover {
  background: linear-gradient(135deg, #059669, #047857);
  transform: translateY(-1px);
  box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.6);
}

/* Modern Form Styles */
.form-item-modern {
  margin-bottom: 24px;
}

:deep(.form-item-modern .ant-form-item-label > label) {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
}

:deep(.input-modern.ant-input-affix-wrapper) {
  border-radius: 12px;
  border: 2px solid transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #f1f5f9, #e2e8f0);
  background-clip: padding-box, border-box;
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  padding: 12px 16px;
}

:deep(.input-modern.ant-input-affix-wrapper:focus),
:deep(.input-modern.ant-input-affix-wrapper-focused) {
  border-color: transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #10b981, #14b8a6);
  background-clip: padding-box, border-box;
  box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
}

:deep(.textarea-modern.ant-input) {
  border-radius: 12px;
  border: 2px solid transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #f1f5f9, #e2e8f0);
  background-clip: padding-box, border-box;
  transition: all 0.3s ease;
  font-size: 14px;
  line-height: 1.6;
  resize: none;
}

:deep(.textarea-modern.ant-input:focus),
:deep(.textarea-modern.ant-input-focused) {
  border-color: transparent;
  background: linear-gradient(white, white), linear-gradient(45deg, #10b981, #14b8a6);
  background-clip: padding-box, border-box;
  box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
}

/* Modal Enhancements */
:deep(.flashcard-set-modal .ant-modal-header) {
  border-radius: 16px 16px 0 0;
  padding: 24px 32px;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid rgba(148, 163, 184, 0.1);
}

:deep(.flashcard-set-modal .ant-modal-title) {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
}

:deep(.flashcard-set-modal .ant-modal-body) {
  padding: 32px;
}

:deep(.flashcard-set-modal .ant-modal-footer) {
  border-radius: 0 0 16px 16px;
  padding: 20px 32px;
  border-top: 1px solid rgba(148, 163, 184, 0.1);
}

/* Drawer Enhancements */
:deep(.flashcard-sets-drawer .ant-drawer-header) {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid rgba(148, 163, 184, 0.1);
  padding: 24px 32px;
}

:deep(.flashcard-sets-drawer .ant-drawer-title) {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
}

:deep(.flashcard-sets-drawer .ant-drawer-body) {
  padding: 0;
  background: #fafbfc;
}

:deep(.flashcard-sets-drawer .ant-drawer-content-wrapper) {
  box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .flashcard-sets-drawer {
    width: 95vw !important;
  }
  
  .flashcard-set-card {
    border-radius: 16px;
    margin-bottom: 16px;
  }
  
  .action-btn {
    width: 36px;
    height: 36px;
  }
  
  :deep(.flashcard-set-modal) {
    width: 95vw !important;
    margin: 0;
    max-width: none;
    top: 20px;
  }
}

@media (max-width: 640px) {
  :deep(.flashcard-sets-drawer .ant-drawer-header),
  :deep(.flashcard-sets-drawer .ant-drawer-body) {
    padding: 16px;
  }
  
  .flashcard-set-card .p-6 {
    padding: 16px;
  }
}

/* Animation for cards */
.flashcard-set-card {
  animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Loading and Empty State Improvements */
.text-center {
  position: relative;
}

.text-center::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 2px;
  background: linear-gradient(90deg, transparent, #10b981, transparent);
  opacity: 0.3;
}

/* Button hover effects */
:deep(.ant-btn-primary) {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.ant-btn-primary:hover) {
  transform: translateY(-1px);
  box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
}

/* Icon animations */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.action-btn svg {
  transition: transform 0.2s ease;
}

.action-btn:hover svg {
  transform: scale(1.1);
}

/* Enhanced shadow effects */
.shadow-xl {
  box-shadow: 
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Gradient text effect for important headings */
.gradient-text {
  background: linear-gradient(135deg, #10b981, #06b6d4);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
