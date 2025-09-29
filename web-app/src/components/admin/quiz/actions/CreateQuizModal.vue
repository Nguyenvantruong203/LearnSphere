<template>
  <a-modal
    :open="open"
    title="Tạo Quiz"
    @ok="handleSubmit"
    @cancel="handleCancel"
    @update:open="emit('update:open', $event)"
  >
    <a-form :model="form" layout="vertical">
      <a-form-item label="Tiêu đề" name="title" required>
        <a-input v-model:value="form.title" />
      </a-form-item>
      <a-form-item label="Thời gian (phút)" name="duration_minutes">
        <a-input-number v-model:value="form.duration_minutes" :min="0" />
      </a-form-item>
      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_questions">Shuffle Questions</a-checkbox>
      </a-form-item>
      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_options">Shuffle Options</a-checkbox>
      </a-form-item>
      <a-form-item label="Số lần làm tối đa (0 = không giới hạn)" name="max_attempts">
        <a-input-number v-model:value="form.max_attempts" :min="0" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { quizApi } from '@/api/admin/quizApi'
import { message } from 'ant-design-vue'

const props = defineProps<{
  open: boolean
  topicId?: number | null
  lessonId?: number | null
  scope: 'lesson' | 'topic'
}>()
const emit = defineEmits(['update:open', 'finish'])

const form = ref({
  title: '',
  duration_minutes: 0,
  shuffle_questions: false,
  shuffle_options: false,
  max_attempts: 0,
})

watch(() => props.open, (val) => {
  if (val) {
    form.value = {
      title: '',
      duration_minutes: 0,
      shuffle_questions: false,
      shuffle_options: false,
      max_attempts: 0,
    }
  }
})

const handleSubmit = async () => {
  try {
    if (props.scope === 'lesson' && props.lessonId) {
      await quizApi.createQuizForLesson(props.lessonId, form.value)
    } else if (props.scope === 'topic' && props.topicId) {
      await quizApi.createQuizForTopic(props.topicId, form.value)
    } else {
      throw new Error('Thiếu topicId hoặc lessonId')
    }
    message.success('Tạo quiz thành công')
    emit('finish', { topicId: props.topicId, lessonId: props.lessonId })
    emit('update:open', false)
  } catch (err: any) {
    message.error(err.message || 'Lỗi khi tạo quiz')
  }
}
const handleCancel = () => emit('update:open', false)
</script>
