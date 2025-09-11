<template>
    <a-modal
        title="Thêm chủ đề mới"
        :visible="visible"
        :confirm-loading="loading"
        ok-text="Tạo"
        cancel-text="Hủy"
        @ok="handleOk"
        @cancel="handleCancel"
    >
        <a-form ref="formRef" :model="formState" :rules="rules" layout="vertical">
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
            <a-form-item label="Thứ tự (tùy chọn)" name="order">
                <a-input-number v-model:value="formState.order" :min="1" style="width: 100%" />
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue';
import { topicApi } from '@/api/topicApi';
import { courseApi } from '@/api/courseApi';
import type { TopicPayload } from '@/types/Topic';
import type { Course } from '@/types/Course';
import { notification } from 'ant-design-vue';
import type { Rule } from 'ant-design-vue/es/form';

interface Props {
    visible: boolean;
}
defineProps<Props>();
const emit = defineEmits(['update:visible', 'finish']);

const formRef = ref();
const loading = ref(false);
const formState = reactive<Partial<TopicPayload>>({
    title: '',
    course_id: undefined,
    order: undefined,
});

const courses = ref<Course[]>([]);

onMounted(async () => {
    try {
        // Lấy tất cả khóa học để chọn
        const res = await courseApi.getCourses({ limit: 999 });
        courses.value = res.data;
    } catch (error) {
        notification.error({ message: 'Không thể tải danh sách khóa học' });
    }
});

const rules: Record<string, Rule[]> = {
    title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
    course_id: [{ required: true, message: 'Vui lòng chọn khóa học!' }],
};

const handleOk = async () => {
    try {
        await formRef.value.validate();
        loading.value = true;
        await topicApi.createTopic(formState as TopicPayload);
        notification.success({ message: 'Tạo chủ đề thành công!' });
        emit('finish');
        handleCancel();
    } catch (error: any) {
        notification.error({ message: error.message || 'Tạo chủ đề thất bại.' });
    } finally {
        loading.value = false;
    }
};

const handleCancel = () => {
    formRef.value.resetFields();
    emit('update:visible', false);
};

const filterOption = (input: string, option: any) => {
  return option.title.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};
</script>
