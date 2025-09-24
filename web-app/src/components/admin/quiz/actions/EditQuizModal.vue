<template>
  <a-modal
    v-model:open="localOpen"
    title="Sửa Quiz"
    @ok="handleSubmit"
    @cancel="handleCancel"
    destroyOnClose
  >
    <a-form :model="form" layout="vertical">
      <a-form-item label="Tiêu đề" name="title" required>
        <a-input v-model:value="form.title" />
      </a-form-item>

      <a-form-item label="Thời gian (phút)" name="duration_minutes">
        <a-input-number v-model:value="form.duration_minutes" :min="0" />
      </a-form-item>

      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_questions">
          Shuffle Questions
        </a-checkbox>
      </a-form-item>

      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_options">
          Shuffle Options
        </a-checkbox>
      </a-form-item>

      <a-form-item
        label="Số lần làm tối đa (0 = không giới hạn)"
        name="max_attempts"
      >
        <a-input-number v-model:value="form.max_attempts" :min="0" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch, nextTick } from 'vue'
import { quizApi } from '@/api/quizApi'
import { message } from 'ant-design-vue'

// Props
const props = defineProps<{
  open: boolean
  quiz: any | null
}>()

// Emits
const emit = defineEmits(['update:open', 'finish'])

// State
const localOpen = ref(false)

const defaultForm = () => ({
  title: '',
  duration_minutes: 0,
  shuffle_questions: false, // Sử dụng boolean
  shuffle_options: false,   // Sử dụng boolean
  max_attempts: 0,
})

const form = ref(defaultForm())

// Populate form từ props.quiz
const populateForm = () => {
  if (!props.quiz) {
    form.value = defaultForm()
    return
  }

  form.value = {
    title: props.quiz.title ?? '',
    duration_minutes: props.quiz.duration_minutes ?? 0,
    shuffle_questions: !!props.quiz.shuffle_questions, // Ép về boolean
    shuffle_options: !!props.quiz.shuffle_options,   // Ép về boolean
    max_attempts: props.quiz.max_attempts ?? 0,
  }
}

// Reset form
const resetForm = () => {
  form.value = defaultForm()
}

// Đồng bộ prop.open → localOpen
watch(
  () => props.open,
  async (val) => {
    localOpen.value = val
    if (val) {
      await nextTick()
      populateForm()
    }
  },
  { immediate: true }
)

// Đồng bộ localOpen → emit update:open
watch(localOpen, (val) => {
  emit('update:open', val)
  if (!val) resetForm()
})

// Nếu quiz thay đổi khi modal đang mở → cập nhật form
watch(
  () => props.quiz,
  (newQuiz) => {
    if (newQuiz && localOpen.value) {
      populateForm()
    }
  },
  { deep: true }
)

// Handle cancel
const handleCancel = () => {
  localOpen.value = false
}

// Submit
const handleSubmit = async () => {
  if (!props.quiz) {
    message.error('Không tìm thấy thông tin quiz')
    return
  }

  const payload = {
    ...form.value,
  }

  try {
    const quizId = props.quiz.id ?? props.quiz.quiz_id
    if (!quizId) {
      message.error('Không tìm thấy ID của quiz để cập nhật.')
      return
    }

    await quizApi.updateQuiz(quizId, payload)
    message.success('Cập nhật quiz thành công')
    emit('finish', {
      lessonId: props.quiz.lesson_id,
      topicId: props.quiz.topic_id,
    })
    localOpen.value = false
  } catch (err: any) {
    message.error(err.message || 'Lỗi khi cập nhật quiz')
  }
}
</script>
