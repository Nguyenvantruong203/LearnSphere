<template>
    <a-modal
        title="Chỉnh sửa chủ đề"
        :visible="visible"
        :confirm-loading="loading"
        ok-text="Cập nhật"
        cancel-text="Hủy"
        @ok="handleOk"
        @cancel="handleCancel"
    >
        <a-form v-if="formState" ref="formRef" :model="formState" :rules="rules" layout="vertical">
            <a-form-item label="Khóa học" name="course_id">
                <a-select
                    v-model:value="formState.course_id"
                    show-search
                    placeholder="Chọn khóa học"
                    :filter-option="filterOption"
                    :options="courses"
                    :field-names="{ label: 'title', value: 'id' }"
                />
            </a-form-item>
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
import { ref, watch, onMounted } from 'vue';
import { topicApi } from '@/api/topicApi';
import { courseApi } from '@/api/courseApi';
import type { Topic, TopicPayload } from '@/types/Topic';
import type { Course } from '@/types/Course';
import { notification } from 'ant-design-vue';
import type { Rule } from 'ant-design-vue/es/form';

interface Props {
    visible: boolean;
    topic: Topic | null;
}
const props = defineProps<Props>();
const emit = defineEmits(['update:visible', 'finish']);

const formRef = ref();
const loading = ref(false);
const formState = ref<Partial<TopicPayload> | null>(null);
const courses = ref<Course[]>([]);

onMounted(async () => {
    try {
        const res = await courseApi.getCourses({ limit: 999 });
        courses.value = res.data;
    } catch (error) {
        notification.error({ message: 'Không thể tải danh sách khóa học' });
    }
});

watch(() => props.topic, (newTopic) => {
    if (newTopic) {
        formState.value = {
            title: newTopic.title,
            course_id: newTopic.course_id,
            order: newTopic.order,
        };
    } else {
        formState.value = null;
    }
}, { immediate: true });

const rules: Record<string, Rule[]> = {
    title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
    course_id: [{ required: true, message: 'Vui lòng chọn khóa học!' }],
};

const handleOk = async () => {
    if (!props.topic || !formState.value) return;

    try {
        await formRef.value.validate();
        loading.value = true;
        await topicApi.updateTopic(props.topic.id, formState.value);
        notification.success({ message: 'Cập nhật chủ đề thành công!' });
        emit('finish');
        handleCancel();
    } catch (error: any) {
        notification.error({ message: error.message || 'Cập nhật chủ đề thất bại.' });
    } finally {
        loading.value = false;
    }
};

const handleCancel = () => {
    emit('update:visible', false);
};

const filterOption = (input: string, option: any) => {
  return option.title.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};
</script>
