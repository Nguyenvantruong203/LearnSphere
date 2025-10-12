<template>
  <a-card class="questions-card" :bordered="false" :body-style="{ padding: '8px' }">
    <!-- Header -->
    <template #title>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-700 flex items-center justify-center shadow-lg shadow-indigo-500/30">
            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
              <line x1="12" x2="12.01" y1="17" y2="17" />
            </svg>
          </div>
          <span class="title-text font-semibold">Question List</span>
          <a-tag v-if="questions.length > 0" color="blue">
            {{ questions.length }} questions
          </a-tag>
        </div>

        <div class="flex items-center gap-2">
          <a-input-number v-model:value="numQuestions" :min="1" :max="20" style="width: 60px" size="small" />

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
            <span class="flex justify-center items-center">
              <PlusOutlined />
              Add Question
            </span>
          </a-button>

          <a-button v-else type="primary" @click="showSelectFromLesson = true">
            <span class="flex justify-center items-center">
              <PlusOutlined />
              Add from Lesson
            </span>
          </a-button>
        </div>
      </div>
    </template>

    <!-- Table -->
    <a-table
      v-if="paginatedQuestions.length > 0"
      :columns="questionColumns"
      :data-source="paginatedQuestions"
      row-key="id"
      :loading="loading"
      size="middle"
      :scroll="{ x: 800 }"
      :pagination="false"
    >
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key === 'index'">
          {{ (pagination.current - 1) * pagination.pageSize + index + 1 }}
        </template>

        <template v-else-if="column.key === 'text'">
          <div :title="record.text">{{ record.text }}</div>
        </template>

        <template v-else-if="column.key === 'type'">
          <a-tag :color="getQuestionTypeColor(record.type)">
            {{ getQuestionTypeIcon(record.type) }}
            {{ getQuestionTypeLabel(record.type) }}
          </a-tag>
        </template>

        <template v-else-if="column.key === 'points'">
          <FormatWeight :weight="record.weight" />
        </template>

        <template v-else-if="column.key === 'action'">
          <a-space>
            <a-tooltip title="Edit">
              <a-button type="text" size="small" @click="openEdit(record)">
                <EditOutlined />
              </a-button>
            </a-tooltip>

            <a-tooltip title="Delete">
              <a-popconfirm
                title="Are you sure you want to delete this question?"
                ok-text="Delete"
                cancel-text="Cancel"
                @confirm="handleDelete(record)"
              >
                <a-button type="text" size="small" danger>
                  <DeleteOutlined />
                </a-button>
              </a-popconfirm>
            </a-tooltip>
          </a-space>
        </template>
      </template>
    </a-table>

    <!-- Empty State -->
    <a-empty v-else-if="!loading" description="No questions yet" class="flex items-center justify-center flex-col gap-4 py-10">
      <template #image>
        <div class="text-3xl">❓</div>
      </template>
      <a-button type="primary" @click="showCreate = true">
        <span class="flex justify-center items-center">
          <PlusOutlined />
          Create First Question
        </span>
      </a-button>
    </a-empty>

    <!-- Pagination -->
    <div v-if="questions.length > 0" class="mt-4 flex justify-end pr-4">
      <a-pagination
        v-model:current="pagination.current"
        v-model:pageSize="pagination.pageSize"
        :total="questions.length"
        show-size-changer
        show-quick-jumper
        :page-size-options="['5', '10', '20', '50']"
        :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} questions`"
      />
    </div>
  </a-card>

  <!-- Modals -->
  <CreateQuestionModal v-if="mode === 'lesson'" :quiz-id="quizId" :visible="showCreate" @close="showCreate = false"
    @created="addQuestion" />

  <SelectFromLessonModal v-if="mode === 'topic'" :quiz-id="quizId" v-model:open="showSelectFromLesson"
    @published="loadQuestions" />

  <EditQuestionModal v-model="showEdit" :question="editingQuestion" :quiz-id="quizId" @updated="updateQuestion" />
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
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
import { EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue'
import FormatWeight from '@/components/common/FormatWeight.vue'

// ===== Props & Emits =====
const props = defineProps<{
  quizId: number
  mode: 'lesson' | 'topic'
}>()

const emit = defineEmits<{
  (e: 'update:questions', value: Question[]): void
  (e: 'delete', value: Question): void
}>()

// ===== STATE =====
const loading = ref(false)
const numQuestions = ref(5)
const questions = ref<Question[]>([])
const showCreate = ref(false)
const showEdit = ref(false)
const editingQuestion = ref<Question | null>(null)
const showSelectFromLesson = ref(false)

// ===== Pagination =====
const pagination = ref({
  current: 1,
  pageSize: 10,
})

// Computed data for current page
const paginatedQuestions = computed(() => {
  const start = (pagination.value.current - 1) * pagination.value.pageSize
  const end = start + pagination.value.pageSize
  return questions.value.slice(start, end)
})

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
    const res =
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
watch(() => props.mode, loadQuestions)

// ===== AI Actions =====
const handleGenerate = async () => {
  if (!props.quizId || numQuestions.value <= 0) return
  loading.value = true
  try {
    const res = await lessonQuestionApi.generateQuestions(props.quizId, numQuestions.value)
    questions.value = [...questions.value, ...res.questions]
    notification.success({ message: 'AI questions generated successfully' })
    emit('update:questions', [...questions.value])
  } finally {
    notification.error
    loading.value = false
  }
}

const handleSuggest = async () => {
  if (!props.quizId || numQuestions.value <= 0) return
  loading.value = true
  try {
    const res = await topicQuestionApi.suggestQuestions(props.quizId, numQuestions.value)
    questions.value = res ?? []
    notification.success({ message: 'AI questions suggested successfully' })
    emit('update:questions', [...questions.value])
  } finally {
    loading.value = false
  }
}

// ===== DELETE =====
const handleDelete = async (record: Question) => {
  try {
    await lessonQuestionApi.deleteQuestion(props.quizId, record.id)
    questions.value = questions.value.filter(q => q.id !== record.id)
    emit('update:questions', [...questions.value])
    notification.success({ message: 'Question deleted successfully' })
  } catch (err: any) {
    notification.error({ message: err?.message || 'Failed to delete question' })
  }
}

// ===== Table Columns =====
const questionColumns = [
  { title: 'No', key: 'index', width: 60, align: 'center' as const },
  { title: 'Question Text', key: 'text', ellipsis: true, minWidth: 300 },
  { title: 'Type', key: 'type', width: 150, align: 'center' as const },
  { title: 'Points', key: 'points', width: 100, align: 'center' as const },
  { title: 'Actions', key: 'action', width: 120, align: 'center' as const },
]
</script>
