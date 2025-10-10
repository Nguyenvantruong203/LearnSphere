<template>
  <a-modal
    :open="visible"
    title="Add New Question"
    ok-text="Create"
    cancel-text="Cancel"
    :confirm-loading="submitting"
    @ok="handleSubmit"
    @cancel="$emit('close')"
    destroy-on-close
  >
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
        <a-textarea v-model:value="form.text" rows="3" placeholder="Enter question content" />
      </a-form-item>

      <!-- For single/multiple -->
      <template v-if="['single', 'multiple'].includes(form.type)">
        <a-form-item label="Answer Options">
          <div
            v-for="([key, label], idx) in Object.entries(form.options)"
            :key="idx"
            class="flex items-center gap-2 mb-2"
          >
            <a-input v-model:value="form.options[key]" :placeholder="`Option ${key}`" />
            <a-checkbox
              :checked="form.correct_options.includes(key)"
              @change="toggleCorrectOption(key, $event.target.checked)"
            >
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
        <a-input-number
          v-model:value="form.weight"
          :min="1"
          :step="0.5"
          style="width: 100%"
          placeholder="Enter points"
        />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { lessonQuestionApi } from '@/api/admin/lessonQuestionApi'
import type { Question } from '@/types/Question'

const props = defineProps<{
  quizId: number
  visible: boolean
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'created', q: Question): void
}>()

const submitting = ref(false)

const form = ref({
  type: 'single',
  text: '',
  options: {
    A: '',
    B: '',
    C: '',
    D: '',
  } as Record<string, string>,
  correct_options: [] as string[],
  weight: 1,
})

// Reset form each time modal opens
watch(
  () => props.visible,
  (val) => {
    if (val) {
      form.value = {
        type: 'single',
        text: '',
        options: { A: '', B: '', C: '', D: '' },
        correct_options: [],
        weight: 1,
      }
    }
  },
)

const handleSubmit = async () => {
  if (!props.quizId) return
  submitting.value = true
  try {
    const q = await lessonQuestionApi.createQuestion(props.quizId, form.value)
    emit('created', q)
    emit('close')
  } finally {
    submitting.value = false
  }
}

const toggleCorrectOption = (key: string, checked: boolean) => {
  if (checked) {
    if (!form.value.correct_options.includes(key)) {
      form.value.correct_options.push(key)
    }
  } else {
    form.value.correct_options = form.value.correct_options.filter((x) => x !== key)
  }
}
</script>
