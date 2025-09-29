<template>
  <a-card class="questions-card" :bordered="false" :body-style="{ padding: '8px' }">
    <template #title>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="title-text">Danh sách câu hỏi</span>
          <a-tag v-if="questions.length > 0" color="blue" class="count-tag">
            {{ questions.length }} câu hỏi
          </a-tag>
        </div>
        <div class="action-buttons flex items-center gap-2">
          <a-input-number v-model:value="numQuestions" :min="1" :max="20" style="width: 60px" size="small" />
          <a-button type="dashed" @click="handleGenerate" :loading="loading"
            :disabled="!numQuestions || numQuestions <= 0" class="generate-btn">
            <template #icon><span class="icon">✨</span></template>
            Generate AI
          </a-button>
          <a-button type="primary" @click="showCreate = true" class="add-btn">
            <template #icon><span class="icon">➕</span></template>
            Thêm câu hỏi
          </a-button>
        </div>
      </div>
    </template>

    <!-- Table -->
    <a-table v-if="questions.length > 0" :columns="questionColumns" :data-source="questions" row-key="id"
      :loading="loading" :pagination="{
        pageSize: 10,
        showSizeChanger: true,
        showQuickJumper: true,
        showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} câu hỏi`
      }" size="middle" class="questions-table" :scroll="{ x: 800 }" :row-class-name="() => 'table-row'">
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key === 'index'">
          <div class="question-index">{{ index + 1 }}</div>
        </template>

        <template v-if="column.key === 'text'">
          <div :title="record.text">{{ record.text }}</div>
        </template>

        <template v-if="column.key === 'type'">
          <a-tag :color="getQuestionTypeColor(record.type)" class="question-type-tag">
            <span class="icon">{{ getQuestionTypeIcon(record.type) }}</span>
            {{ getQuestionTypeLabel(record.type) }}
          </a-tag>
        </template>

        <template v-if="column.key === 'points'">
          <div class="points-display">
            <span class="points-value">{{ record.weight || 1 }}</span>
          </div>
        </template>

        <template v-if="column.key === 'action'">
          <a-space>
            <a-tooltip title="Chỉnh sửa">
              <a-button type="text" size="small" @click="openEditQuestion(record)" class="action-btn edit-btn">
                <template #icon><span class="icon">✏️</span></template>
              </a-button>

            </a-tooltip>
            <a-tooltip title="Xóa">
              <a-popconfirm title="Bạn có chắc muốn xóa câu hỏi này?" ok-text="Xóa" cancel-text="Hủy"
                @confirm="$emit('delete', record)">
                <a-button type="text" size="small" danger class="action-btn delete-btn">
                  <template #icon><span class="icon">🗑️</span></template>
                </a-button>
              </a-popconfirm>
            </a-tooltip>
          </a-space>
        </template>
      </template>
    </a-table>

    <!-- Empty State -->
    <a-empty v-else-if="!loading" description="Chưa có câu hỏi nào" class="empty-state">
      <template #image>
        <div class="empty-icon">❓</div>
      </template>
      <div class="empty-actions">
        <a-button type="primary" @click="showCreate = true" class="empty-add-btn">
          <template #icon><span class="icon">➕</span></template>
          Tạo câu hỏi đầu tiên
        </a-button>
      </div>
    </a-empty>
  </a-card>

  <CreateQuestionModal :quiz-id="quizId" :visible="showCreate" @close="showCreate = false"
    @created="openCreateQuestion" />
  <EditQuestionModal v-model="showEdit" :question="editingQuestion" :quiz-id="quizId" @updated="openUpdateQuestion" />

</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { lessonQuestionApi } from '@/api/admin/lessonQuestionApi'
import type { Question } from '@/types/Question'
import {
  getQuestionTypeColor,
  getQuestionTypeIcon,
  getQuestionTypeLabel,
} from '@/components/admin/question/utils/TableQuestionType'
import CreateQuestionModal from '@/components/admin/question/actions/CreateQuestionModal.vue'
import EditQuestionModal from '@/components/admin/question/actions/EditQuestionModal.vue'

const props = defineProps<{
  quizId: number
}>()

const emit = defineEmits(['add', 'edit', 'delete'])
const loading = ref(false)
const numQuestions = ref(5)
const questions = ref<Question[]>([])
const showCreate = ref(false)
const showEdit = ref(false)
const editingQuestion = ref(null)

const openCreateQuestion = (q) => {
  questions.value.push(q)
}

const openEditQuestion = (q: Question) => {
  editingQuestion.value = q
  showEdit.value = true
}

const openUpdateQuestion = (q: Question) => {
  const index = questions.value.findIndex((item) => item.id === q.id)
  if (index !== -1) {
    questions.value[index] = q
  }
}
const loadQuestions = async () => {
  if (!props.quizId) return
  loading.value = true
  try {
    const res = await lessonQuestionApi.getQuestions(props.quizId, { per_page: 50 })
    questions.value = res.data ?? res
  } finally {
    loading.value = false
  }
}

onMounted(loadQuestions)
watch(() => props.quizId, loadQuestions)

const handleGenerate = async () => {
  if (!props.quizId || numQuestions.value <= 0) return
  loading.value = true
  try {
    const res = await lessonQuestionApi.generateQuestions(props.quizId, numQuestions.value)
    questions.value = [...questions.value, ...res.questions]
  } finally {
    loading.value = false
  }
}

const questionColumns = [
  { title: '#', key: 'index', width: 60, align: 'center' as const, fixed: 'left' as const },
  { title: 'Nội dung câu hỏi', key: 'text', ellipsis: true, minWidth: 300 },
  { title: 'Loại', key: 'type', width: 150, align: 'center' as const },
  { title: 'Điểm', key: 'points', width: 100, align: 'center' as const },
  { title: 'Thao tác', key: 'action', width: 120, align: 'center' as const, fixed: 'right' as const }
]
</script>
