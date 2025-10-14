<template>
  <a-modal
    v-model:open="open"
    title="Edit Lesson"
    ok-text="Save"
    cancel-text="Cancel"
    :confirm-loading="loading"
    @ok="handleSubmit"
  >
    <a-form :model="form" :rules="rules" ref="formRef" layout="vertical">
      <a-form-item label="Title" name="title" required>
        <a-input v-model:value="form.title" placeholder="Enter lesson title" />
      </a-form-item>

      <a-form-item label="Content" name="content">
        <a-textarea
          v-model:value="form.content"
          placeholder="Enter lesson content"
          rows="4"
        />
      </a-form-item>

      <a-form-item label="Order" name="order">
        <a-input-number
          v-model:value="form.order"
          :min="1"
          style="width: 100%"
          placeholder="Enter display order"
        />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { notification } from 'ant-design-vue'
import { lessonApi } from '@/api/instructor/lessonApi'

interface Lesson {
  id: number
  title: string
  content?: string | null
  order: number
  topic_id?: number
}

const props = defineProps<{
  open: boolean
  lesson: Lesson | null
}>()

const emit = defineEmits<{
  (e: 'update:open', v: boolean): void
  (e: 'finish', payload: { topicId: number; courseId?: number }): void
}>()

// Proxy for v-model binding
const open = computed({
  get: () => props.open,
  set: (val: boolean) => emit('update:open', val),
})

const form = ref({
  title: '',
  content: '',
  order: 1,
})

const rules = {
  title: [{ required: true, message: 'Title cannot be empty', trigger: 'blur' }],
  content: [
    {
      required: true,
      message: 'Please enter the lesson content!',
      trigger: 'blur',
      type: 'string',
    },
  ],
  order: [
    {
      required: true,
      type: 'number',
      min: 1,
      message: 'Order must be greater than 0!',
      trigger: 'blur',
    },
  ],
}

const loading = ref(false)
const formRef = ref()

watch(
  () => props.lesson,
  (newLesson) => {
    if (newLesson) {
      form.value = {
        title: newLesson.title || '',
        content: newLesson.content || '',
        order: newLesson.order || 1,
      }
    }
  },
  { immediate: true }
)

const handleSubmit = async () => {
  try {
    await formRef.value?.validate()
    if (!props.lesson?.id) throw new Error('Missing lesson ID')

    loading.value = true
    await lessonApi.updateLesson(props.lesson.id, form.value)

    notification.success({ message: 'Lesson updated successfully!' })
    if (props.lesson.topic_id) {
      emit('finish', { topicId: props.lesson.topic_id })
    } else {
      emit('finish', { topicId: -1 }) // fallback
    }
    open.value = false
  } catch (error: any) {
    notification.error({
      message: error?.message || 'Failed to update lesson.',
    })
  } finally {
    loading.value = false
  }
}
</script>
