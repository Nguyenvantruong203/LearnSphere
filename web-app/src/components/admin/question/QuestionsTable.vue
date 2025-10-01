<template>
  <a-card class="questions-card" :bordered="false" :body-style="{ padding: '8px' }">
    <!-- Header -->
    <template #title>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="title-text">Danh sách câu hỏi</span>
          <a-tag v-if="questions.length > 0" color="blue">
            {{ questions.length }} câu hỏi
          </a-tag>
        </div>
        <div class="flex items-center gap-2">
          <a-input-number v-model:value="numQuestions" :min="1" :max="50" style="width: 60px" size="small" />

          <!-- Action buttons -->
          <a-button v-if="mode === 'lesson'" type="dashed" @click="handleGenerate" :loading="loading"
            :disabled="numQuestions <= 0">
            <template #icon>✨</template>
            Generate AI
          </a-button>

          <a-button v-else type="dashed" @click="handleSuggest" :loading="loading" :disabled="numQuestions <= 0">
            <template #icon>💡</template>
            Suggest AI
          </a-button>

          <a-button v-if="mode === 'lesson'" type="primary" @click="showCreate = true">
            <template #icon>➕</template>
            Thêm câu hỏi
          </a-button>

          <a-button v-else type="primary" @click="showSelectFromLesson = true">
            <template #icon>➕</template>
            Thêm từ Lesson
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
        showTotal: (t, r) => `${r[0]}-${r[1]} của ${t} câu hỏi`
      }" size="middle" :scroll="{ x: 800 }">
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key === 'index'">{{ index + 1 }}</template>

        <template v-if="column.key === 'text'">
          <div :title="record.text">{{ record.text }}</div>
        </template>

        <template v-if="column.key === 'type'">
          <a-tag :color="getQuestionTypeColor(record.type)">
            {{ getQuestionTypeIcon(record.type) }}
            {{ getQuestionTypeLabel(record.type) }}
          </a-tag>
        </template>

        <template v-if="column.key === 'points'">
          <span>{{ record.weight || 1 }}</span>
        </template>

        <template v-if="column.key === 'action'">
          <a-space>
            <a-tooltip title="Chỉnh sửa">
              <a-button type="text" size="small" @click="openEdit(record)">
                ✏️
              </a-button>
            </a-tooltip>
            <a-tooltip title="Xóa">
              <a-popconfirm title="Bạn có chắc muốn xóa câu hỏi này?" ok-text="Xóa" cancel-text="Hủy"
                @confirm="handleDelete(record)">
                <a-button type="text" size="small" danger>
                  🗑️
                </a-button>
              </a-popconfirm>
            </a-tooltip>
          </a-space>
        </template>
      </template>
    </a-table>

    <!-- Empty -->
    <a-empty v-else-if="!loading" description="Chưa có câu hỏi nào">
      <template #image>
        <div class="text-3xl">❓</div>
      </template>
      <a-button type="primary" @click="showCreate = true">
        <template #icon>➕</template>
        Tạo câu hỏi đầu tiên
      </a-button>
    </a-empty>
  </a-card>

  <!-- Modals -->
  <CreateQuestionModal v-if="mode === 'lesson'" :quiz-id="quizId" :visible="showCreate" @close="showCreate = false"
    @created="addQuestion" />

  <SelectFromLessonModal v-if="mode === 'topic'" :quiz-id="quizId" v-model:open="showSelectFromLesson"
    @published="loadQuestions" />
  <EditQuestionModal v-model="showEdit" :question="editingQuestion" :quiz-id="quizId" @updated="updateQuestion" />
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { lessonQuestionApi } from '@/api/admin/lessonQuestionApi'
import { topicQuestionApi } from '@/api/admin/topicQuestionApi'
import { notification } from 'ant-design-vue'
import type { Question } from '@/types/Question'
import {
  getQuestionTypeColor,
  getQuestionTypeIcon,
  getQuestionTypeLabel,
} from '@/components/admin/question/utils/TableQuestionType'
import CreateQuestionModal from '@/components/admin/question/actions/forLesson/CreateQuestionModal.vue'
import EditQuestionModal from '@/components/admin/question/actions/forLesson/EditQuestionModal.vue'
import SelectFromLessonModal from '@/components/admin/question/actions/forTopic/SelectFromLessonModal.vue'

const props = defineProps<{
  quizId: number
  mode: 'lesson' | 'topic'
}>()

const emit = defineEmits<{
  (e: 'update:questions', value: Question[]): void
  (e: 'delete', value: Question): void
}>()

// state
const loading = ref(false)
const numQuestions = ref(5)
const questions = ref<Question[]>([])
const showCreate = ref(false)
const showEdit = ref(false)
const editingQuestion = ref<Question | null>(null)
const showSelectFromLesson = ref(false)
// ===== CRUD =====
const addQuestion = (q: Question) => {
  questions.value.push(q)
  emit('update:questions', [...questions.value])
}

const openEdit = (q: Question) => {
  editingQuestion.value = q
  showEdit.value = true
}

const updateQuestion = (q: Question) => {
  const idx = questions.value.findIndex(i => i.id === q.id)
  if (idx !== -1) questions.value[idx] = q
  emit('update:questions', [...questions.value])
}

// ===== LOAD DATA =====
const loadQuestions = async () => {
  if (!props.quizId) return
  loading.value = true
  try {
    let res
    res =
      props.mode === 'lesson'
        ? await lessonQuestionApi.getQuestions(props.quizId, { per_page: 50 })
        : await topicQuestionApi.getQuestions(props.quizId, { per_page: 50 })

    questions.value = res.data ?? res
    emit('update:questions', [...questions.value])
  } finally {
    loading.value = false
  }
}

watch(() => props.quizId, loadQuestions, { immediate: true })
watch(() => props.mode, (newMode, oldMode) => {
  console.log('QuestionsTable - Mode changed from', oldMode, 'to', newMode, 'for quizId:', props.quizId)
  loadQuestions()
})

// ===== AI Actions =====
const handleGenerate = async () => {
  if (!props.quizId || numQuestions.value <= 0) return
  loading.value = true
  try {
    const res = await lessonQuestionApi.generateQuestions(props.quizId, numQuestions.value)
    questions.value = [...questions.value, ...res.questions]
    emit('update:questions', [...questions.value])
  } finally {
    loading.value = false
  }
}

const handleSuggest = async () => {
  if (!props.quizId || numQuestions.value <= 0) return
  loading.value = true
  try {
    const res = await topicQuestionApi.suggestQuestions(props.quizId, numQuestions.value)
    questions.value = res ?? []
    emit('update:questions', [...questions.value])
  } finally {
    loading.value = false
  }
}

const handleDelete = async (record: Question) => {
  try {
    await lessonQuestionApi.deleteQuestion(props.quizId, record.id)
    questions.value = questions.value.filter(q => q.id !== record.id)
    emit('update:questions', [...questions.value])
    notification.success({ message: 'Đã xoá câu hỏi' })
  } catch (err: any) {
    notification.error({ message: err?.message || 'Xoá câu hỏi thất bại' })
  }
}


// ===== Table Columns =====
const questionColumns = [
  { title: '#', key: 'index', width: 60, align: 'center' as const },
  { title: 'Nội dung câu hỏi', key: 'text', ellipsis: true, minWidth: 300 },
  { title: 'Loại', key: 'type', width: 150, align: 'center' as const },
  { title: 'Điểm', key: 'points', width: 100, align: 'center' as const },
  { title: 'Thao tác', key: 'action', width: 120, align: 'center' as const },
]
</script>
