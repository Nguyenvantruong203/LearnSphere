<template>
  <a-modal v-model:open="props.modelValue" title="Edit Question" ok-text="Save" cancel-text="Cancel"
    :confirm-loading="submitting" @ok="handleSubmit" @cancel="emit('update:modelValue', false)" destroy-on-close>
    <a-form :model="form" layout="vertical">
      <a-form-item label="Question Type" required>
        <a-select v-model:value="form.type" placeholder="Select question type">
          <a-select-option value="single">Single Choice</a-select-option>
          <a-select-option value="multiple">Multiple Choice</a-select-option>
          <a-select-option value="true_false">True / False</a-select-option>
          <a-select-option value="essay">Essay</a-select-option>
        </a-select>
      </a-form-item>

      <a-form-item label="Question Text" required>
        <a-textarea v-model:value="form.text" rows="3" placeholder="Enter question text" />
      </a-form-item>

      <!-- For single/multiple -->
      <template v-if="['single', 'multiple'].includes(form.type)">
        <a-form-item label="Answer Options">
          <div v-for="([key, label], idx) in Object.entries(form.options)" :key="idx"
            class="flex items-center gap-2 mb-2">
            <a-input v-model:value="form.options[key]" :placeholder="`Option ${key}`" />
            <a-checkbox :checked="form.correct_options.includes(key)"
              @change="toggleCorrectOption(key, $event.target.checked)">
              Correct
            </a-checkbox>
          </div>
        </a-form-item>
      </template>

      <!-- For true/false -->
      <template v-else-if="form.type === 'true_false'">
        <a-form-item label="Correct Answer">
          <a-radio-group v-model:value="form.correct_options[0]">
            <a-radio value="A">True</a-radio>
            <a-radio value="B">False</a-radio>
          </a-radio-group>
        </a-form-item>
      </template>

      <a-form-item label="Points">
        <a-input-number v-model:value="form.weight" :min="1" :step="0.5" style="width: 100%" placeholder="Enter points"
          :formatter="formatPoints" :parser="parsePoints" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { lessonQuestionApi } from '@/api/instructor/lessonQuestionApi'
import type { Question } from '@/types/Question'

const props = defineProps<{
  quizId: number
  question: Question | null
  modelValue: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'updated', q: Question): void
}>()

const submitting = ref(false)

const form = ref({
  type: 'single',
  text: '',
  options: { A: '', B: '', C: '', D: '' } as Record<string, string>,
  correct_options: [] as string[],
  weight: 1,
})

// Load data from props.question
watch(
  () => props.question,
  (q) => {
    if (q) {
      form.value = {
        type: q.type,
        text: q.text,
        options:
          q.options && Object.keys(q.options).length
            ? q.options
            : { A: '', B: '', C: '', D: '' },
        correct_options: q.correct_options || [],
        weight: q.weight || 1,
      }
    }
  },
  { immediate: true },
)

const toggleCorrectOption = (key: string, checked: boolean) => {
  if (checked) {
    if (!form.value.correct_options.includes(key)) {
      form.value.correct_options.push(key)
    }
  } else {
    form.value.correct_options = form.value.correct_options.filter((x) => x !== key)
  }
}

const handleSubmit = async () => {
  if (!props.question) return
  submitting.value = true
  try {
    const q = await lessonQuestionApi.updateQuestion(
      props.quizId,
      props.question.id,
      form.value
    )
    emit('updated', q)
    emit('update:modelValue', false)
  } finally {
    submitting.value = false
  }
}

const formatPoints = (value?: number | string) => {
  if (value === undefined || value === null || value === '') return ''
  const num = Number(value)
  if (Number.isInteger(num)) return String(num)
  return String(num)
}

const parsePoints = (value: string) => {
  return parseFloat(value.replace(/[^\d.]/g, ''))
}
</script>
