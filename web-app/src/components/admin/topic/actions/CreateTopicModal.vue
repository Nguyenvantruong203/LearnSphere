<template>
  <a-modal
    title="Tạo chủ đề mới"
    :open="open"
    :confirm-loading="loading"
    ok-text="Tạo"
    cancel-text="Hủy"
    @ok="handleFinish"
    @cancel="handleCancel"
  >
    <a-form ref="formRef" :model="formState" :rules="rules" layout="vertical">
      <a-form-item label="Tiêu đề" name="title">
        <a-input v-model:value="formState.title" />
      </a-form-item>
      <a-form-item label="Thứ tự (tùy chọn)" name="order">
        <a-input-number v-model:value="formState.order" :min="1" style="width: 100%" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { topicApi } from '@/api/topicApi'
import type { TopicPayload } from '@/types/Topic'
import { notification } from 'ant-design-vue'
import type { Rule } from 'ant-design-vue/es/form'

interface Props {
  open: boolean
  courseId: number
}
const props = defineProps<Props>()
const emit = defineEmits(['update:open', 'finish'])

const formRef = ref()
const loading = ref(false)
const formState = reactive<Partial<TopicPayload>>({
  title: '',
  order: undefined,
  course_id: undefined,
})

watch(
  () => props.open,
  (newVal) => {
    if (newVal) {
      formState.course_id = props.courseId
    } else {
      formRef.value?.resetFields()
      Object.assign(formState, { title: '', order: undefined, course_id: undefined })
    }
  }
)

const rules: Record<string, Rule[]> = {
  title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
}

const handleFinish = async () => {
  try {
    await formRef.value.validate()
    loading.value = true
    await topicApi.createTopic(formState as TopicPayload)
    notification.success({ message: 'Tạo chủ đề thành công!' })
    emit('finish')
    handleCancel()
  } catch (error: any) {
    notification.error({ message: error.message || 'Tạo chủ đề thất bại.' })
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  formRef.value?.resetFields()
  Object.assign(formState, { title: '', order: undefined, course_id: undefined })
  emit('update:open', false)
}
</script>
