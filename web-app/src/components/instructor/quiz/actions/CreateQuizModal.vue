<template>
  <a-modal
    :open="open"
    title="Create Quiz"
    ok-text="Create"
    cancel-text="Cancel"
    @ok="handleSubmit"
    @cancel="handleCancel"
    @update:open="emit('update:open', $event)"
  >
    <a-form :model="form" layout="vertical">
      <a-form-item label="Title" name="title" required>
        <a-input v-model:value="form.title" placeholder="Enter quiz title" />
      </a-form-item>

      <a-form-item label="Duration (minutes)" name="duration_minutes" required>
        <a-input-number
          v-model:value="form.duration_minutes"
          :min="0"
          style="width: 100%"
          placeholder="Enter duration in minutes"
        />
      </a-form-item>

      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_questions">Shuffle Questions</a-checkbox>
      </a-form-item>

      <a-form-item>
        <a-checkbox v-model:checked="form.shuffle_options">Shuffle Options</a-checkbox>
      </a-form-item>

      <a-form-item label="Maximum Attempts (0 = Unlimited)" name="max_attempts" required>
        <a-input-number
          v-model:value="form.max_attempts"
          :min="0"
          style="width: 100%"
          placeholder="Enter maximum attempts"
        />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { quizApi } from '@/api/instructor/quizApi'
import { notification } from 'ant-design-vue'

const props = defineProps<{
  open: boolean
  topicId?: number | null
  lessonId?: number | null
  scope: 'lesson' | 'topic'
}>()
const emit = defineEmits(['update:open', 'finish'])

const form = ref({
  title: '',
  duration_minutes: null,
  shuffle_questions: false,
  shuffle_options: false,
  max_attempts: null,
})

watch(() => props.open, (val) => {
  if (val) {
    form.value = {
      title: '',
      duration_minutes: null,
      shuffle_questions: false,
      shuffle_options: false,
      max_attempts: null,
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
      throw new Error('Missing topicId or lessonId')
    }

    notification.success({ message: 'Quiz created successfully!' })
    emit('finish', { topicId: props.topicId, lessonId: props.lessonId })
    emit('update:open', false)
  } catch (err: any) {
    notification.error({
      message: err.message || 'Failed to create quiz.',
    })
  }
}

const handleCancel = () => emit('update:open', false)
</script>
