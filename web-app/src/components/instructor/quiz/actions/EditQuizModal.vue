<template>
  <a-modal
    v-model:open="localOpen"
    title="Edit Quiz"
    ok-text="Save"
    cancel-text="Cancel"
    @ok="handleSubmit"
    @cancel="handleCancel"
    destroyOnClose
  >
    <a-form :model="form" layout="vertical">
      <a-form-item label="Title" name="title" required>
        <a-input v-model:value="form.title" placeholder="Enter quiz title" />
      </a-form-item>

      <a-form-item label="Duration (minutes)" name="duration_minutes">
        <a-input-number
          v-model:value="form.duration_minutes"
          :min="0"
          style="width: 100%"
          placeholder="Enter duration in minutes"
        />
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
        label="Maximum Attempts (0 = Unlimited)"
        name="max_attempts"
      >
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
import { ref, watch, nextTick } from 'vue'
import { quizApi } from '@/api/instructor/quizApi'
import { notification } from 'ant-design-vue'

// Props
const props = defineProps<{
  open: boolean
  quiz: any | null
}>()

// Emits
const emit = defineEmits(['update:open', 'finish'])

// Local modal state
const localOpen = ref(false)

// Default form data
const defaultForm = () => ({
  title: '',
  duration_minutes: null,
  shuffle_questions: false,
  shuffle_options: false,
  max_attempts: null,
})

const form = ref(defaultForm())

// Populate form from props.quiz
const populateForm = () => {
  if (!props.quiz) {
    form.value = defaultForm()
    return
  }

  form.value = {
    title: props.quiz.title ?? '',
    duration_minutes: props.quiz.duration_minutes,
    shuffle_questions: !!props.quiz.shuffle_questions,
    shuffle_options: !!props.quiz.shuffle_options,
    max_attempts: props.quiz.max_attempts,
  }
}

// Reset form
const resetForm = () => {
  form.value = defaultForm()
}

// Sync prop.open → localOpen
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

// Sync localOpen → emit update:open
watch(localOpen, (val) => {
  emit('update:open', val)
  if (!val) resetForm()
})

// Update form if quiz changes while modal is open
watch(
  () => props.quiz,
  (newQuiz) => {
    if (newQuiz && localOpen.value) {
      populateForm()
    }
  },
  { deep: true }
)

// Cancel
const handleCancel = () => {
  localOpen.value = false
}

// Submit
const handleSubmit = async () => {
  if (!props.quiz) {
    notification.error({ message: 'No quiz available to update.' })
    return
  }

  const payload = { ...form.value }

  try {
    const quizId = props.quiz.id ?? props.quiz.quiz_id
    if (!quizId) {
      notification.error({ message: 'Quiz ID not found for update.' })
      return
    }

    await quizApi.updateQuiz(quizId, payload)
    notification.success({ message: 'Quiz updated successfully!' })
    emit('finish', {
      lessonId: props.quiz.lesson_id,
      topicId: props.quiz.topic_id,
    })
    localOpen.value = false
  } catch (err: any) {
    notification.error({
      message: err.message || 'Failed to update quiz.',
    })
  }
}
</script>
