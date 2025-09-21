<template>
  <a-modal
    v-model:open="open"
    title="Chỉnh sửa bài học"
    ok-text="Lưu"
    cancel-text="Hủy"
    :confirm-loading="loading"
    @ok="handleSubmit"
  >
    <a-form :model="form" :rules="rules" ref="formRef" layout="vertical">
      <a-form-item label="Tiêu đề" name="title" required>
        <a-input v-model:value="form.title" placeholder="Nhập tiêu đề bài học" />
      </a-form-item>

      <a-form-item label="Nội dung" name="content">
        <a-textarea v-model:value="form.content" placeholder="Nhập nội dung bài học" rows="4" />
      </a-form-item>

      <a-form-item label="Thứ tự" name="order">
        <a-input-number v-model:value="form.order" :min="1" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { notification } from 'ant-design-vue'
import { lessonApi } from '@/api/lessonApi'

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

// proxy open prop để không mutate trực tiếp
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
  title: [{ required: true, message: 'Tiêu đề không được để trống', trigger: 'blur' }],
  order: [
    {
      required: true,
      type: 'number',
      min: 1,
      message: 'Thứ tự phải lớn hơn 0',
      trigger: 'change',
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
    if (!props.lesson?.id) throw new Error('Thiếu ID bài học')

    loading.value = true
    await lessonApi.updateLesson(props.lesson.id, form.value)

    notification.success({ message: 'Cập nhật bài học thành công!' })
    if (props.lesson.topic_id) {
      emit('finish', { topicId: props.lesson.topic_id })
    } else {
      emit('finish', { topicId: -1 }) // fallback
    }
    open.value = false
  } catch (error: any) {
    notification.error({
      message: error?.message || 'Cập nhật bài học thất bại.',
    })
  } finally {
    loading.value = false
  }
}
</script>
