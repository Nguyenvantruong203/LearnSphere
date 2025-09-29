<template>
  <a-modal
    :open="visible"
    title="Thêm câu hỏi mới"
    ok-text="Tạo"
    cancel-text="Hủy"
    :confirm-loading="submitting"
    @ok="handleSubmit"
    @cancel="$emit('close')"
    destroy-on-close
  >
    <a-form :model="form" layout="vertical">
      <a-form-item label="Loại câu hỏi" required>
        <a-select v-model:value="form.type" placeholder="Chọn loại câu hỏi">
          <a-select-option value="single">Một đáp án</a-select-option>
          <a-select-option value="multiple">Nhiều đáp án</a-select-option>
          <a-select-option value="true_false">Đúng / Sai</a-select-option>
          <a-select-option value="essay">Tự luận</a-select-option>
        </a-select>
      </a-form-item>

      <a-form-item label="Nội dung câu hỏi" required>
        <a-textarea v-model:value="form.text" rows="3" />
      </a-form-item>

      <!-- Với single/multiple -->
      <template v-if="['single', 'multiple'].includes(form.type)">
        <a-form-item label="Các đáp án">
          <div
            v-for="([key, label], idx) in Object.entries(form.options)"
            :key="idx"
            class="flex items-center gap-2 mb-2"
          >
            <a-input v-model:value="form.options[key]" :placeholder="`Đáp án ${key}`" />
            <a-checkbox
              :checked="form.correct_options.includes(key)"
              @change="toggleCorrectOption(key, $event.target.checked)"
            >
              Đúng
            </a-checkbox>
          </div>
        </a-form-item>
      </template>

      <!-- Với true_false -->
      <template v-else-if="form.type === 'true_false'">
        <a-form-item label="Đáp án đúng">
          <a-radio-group v-model:value="form.correct_options[0]">
            <a-radio value="A">Đúng</a-radio>
            <a-radio value="B">Sai</a-radio>
          </a-radio-group>
        </a-form-item>
      </template>

      <a-form-item label="Điểm số">
        <a-input-number v-model:value="form.weight" :min="1" :step="0.5" style="width: 100%" />
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

// reset mỗi khi mở
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
