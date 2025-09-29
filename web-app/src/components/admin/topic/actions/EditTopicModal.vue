<template>
    <a-modal title="Chỉnh sửa chủ đề" :open="open" :confirm-loading="loading" ok-text="Cập nhật" cancel-text="Hủy"
        @ok="handleFinish" @cancel="handleCancel">
        <a-form v-if="props.topic" ref="formRef" :model="formState" :rules="rules" layout="vertical">
            <a-form-item label="Tiêu đề" name="title">
                <a-input v-model:value="formState.title" />
            </a-form-item>
            <a-form-item label="Thứ tự" name="order">
                <a-input-number v-model:value="formState.order" :min="1" style="width: 100%" />
            </a-form-item>
        </a-form>
        <a-skeleton v-else active />
    </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue';
import { topicApi } from '@/api/admin/topicApi';
import type { Topic, TopicPayload } from '@/types/Topic';
import { notification } from 'ant-design-vue';
import type { Rule } from 'ant-design-vue/es/form';

interface Props {
    open: boolean;
    topic: Topic | null;
}
const props = defineProps<Props>();
const emit = defineEmits(['update:open', 'finish']);

const formRef = ref();
const loading = ref(false);
const formState = reactive<Partial<TopicPayload>>({
    title: '',
    order: undefined,
    course_id: undefined,
});

watch(() => props.topic, (newTopic) => {
    if (newTopic) {
        console.log('Topic vào modal:', newTopic)
        Object.assign(formState, {
            title: newTopic.title,
            order: newTopic.order,
            course_id: (newTopic as any).course_id ?? undefined,
        });
    } else {
        formRef.value?.resetFields();
        Object.assign(formState, { title: '', order: undefined, course_id: undefined });
    }
}, { immediate: true })


const rules: Record<string, Rule[]> = {
    title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
    order: [{ required: true, message: 'Vui lòng nhập thứ tự!' }],
};

const handleFinish = async () => {
  if (!props.topic?.id) {
    notification.error({ message: 'Không tìm thấy ID của chủ đề!' })
    return
  }

  try {
    await formRef.value.validate()
    loading.value = true

    // clone reactive -> plain object
    const payload = {
      title: formState.title,
      order: formState.order,
      course_id: formState.course_id,
    }

    await topicApi.updateTopic(props.topic.id, payload)

    notification.success({ message: 'Cập nhật chủ đề thành công!' })
    emit('finish', { courseId: payload.course_id ?? (props.topic as any)?.course_id ?? undefined })
    handleCancel()
  } catch (error: any) {
    console.error('Update error:', error)
    notification.error({ message: error.message || 'Cập nhật chủ đề thất bại.' })
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
    formRef.value?.resetFields();
    Object.assign(formState, { title: '', order: undefined, course_id: undefined });
    emit('update:open', false);
};
</script>
