<template>
    <a-modal title="Thêm khóa học mới" :open="open" :confirm-loading="loading" ok-text="Tạo" cancel-text="Hủy"
        @ok="handleFinish" @cancel="handleCancel" width="800px">
        <a-form ref="formRef" :model="formState" :rules="rules" layout="vertical">
            <a-form-item label="Ảnh bìa" name="thumbnail">
                <a-upload v-model:file-list="fileList" list-type="picture-card" :before-upload="() => false"
                    @change="handleThumbnailChange" @preview="handlePreview" :max-count="1">
                    <div v-if="fileList.length < 1">
                        <plus-outlined />
                        <div style="margin-top: 8px">Tải lên</div>
                    </div>
                </a-upload>
            </a-form-item>
            <a-row :gutter="16">
                <a-col :span="12">
                    <a-form-item label="Tiêu đề" name="title">
                        <a-input v-model:value="formState.title" />
                    </a-form-item>
                </a-col>
                <a-col :span="6">
                    <a-form-item label="Chủ đề" name="subject">
                        <a-select v-model:value="formState.subject" placeholder="Chọn chủ đề" style="width: 100%"
                            allowClear>
                            <a-select-option value="it">IT</a-select-option>
                            <a-select-option value="design">Design</a-select-option>
                            <a-select-option value="development">Development</a-select-option>
                            <a-select-option value="business">Business</a-select-option>
                            <a-select-option value="marketing">Marketing</a-select-option>
                            <a-select-option value="finance">Finance</a-select-option>
                            <a-select-option value="language">Language</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="Giá" name="price">
                        <a-input-number v-model:value="formState.price" style="width: 100%" :min="0" />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-form-item label="Mô tả ngắn" name="short_description">
                <a-textarea v-model:value="formState.short_description" :rows="3" />
            </a-form-item>
            <a-row :gutter="16">
                <a-col :span="8">
                    <a-form-item label="Trạng thái" name="status">
                        <a-select v-model:value="formState.status">
                            <a-select-option value="draft">Bản nháp</a-select-option>
                            <a-select-option value="published">Công khai</a-select-option>
                            <a-select-option value="archived">Lưu trữ</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Mức độ" name="level">
                        <a-select v-model:value="formState.level">
                            <a-select-option value="beginner">Cơ bản</a-select-option>
                            <a-select-option value="intermediate">Trung bình</a-select-option>
                            <a-select-option value="advanced">Nâng cao</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Ngôn ngữ" name="language">
                        <a-select v-model:value="formState.language">
                            <a-select-option value="vi">VN</a-select-option>
                            <a-select-option value="en">ENG</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </a-modal>
    <a-modal :visible="previewVisible" :title="previewTitle" :footer="null" @cancel="handlePreviewCancel">
        <img alt="example" style="width: 100%" :src="previewImage" />
    </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { courseApi } from '@/api/admin/courseApi';
import type { CoursePayload } from '@/types/Course';
import { notification } from 'ant-design-vue';
import type { Rule } from 'ant-design-vue/es/form';
import { PlusOutlined } from '@ant-design/icons-vue';
import type { UploadChangeParam, UploadFile } from 'ant-design-vue';

interface Props {
    open: boolean;
}
const props = defineProps<Props>();
const emit = defineEmits(['update:open', 'finish']);

const formRef = ref();
const loading = ref(false);
const thumbnailFile = ref<File | null>(null);
const fileList = ref<UploadFile[]>([]);

// Preview logic
const previewVisible = ref(false);
const previewImage = ref('');
const previewTitle = ref('');

function getBase64(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result as string);
        reader.onerror = error => reject(error);
    });
}

const handlePreviewCancel = () => {
    previewVisible.value = false;
    previewTitle.value = '';
};

const handlePreview = async (file: UploadFile) => {
    if (!file.url && !file.preview) {
        file.preview = await getBase64(file.originFileObj as File);
    }
    previewImage.value = file.url || file.preview || '';
    previewVisible.value = true;
    previewTitle.value = file.name || (file.url ? file.url.substring(file.url.lastIndexOf('/') + 1) : '');
};

const initialFormState: CoursePayload = {
    title: '',
    short_description: '',
    description: '',
    price: 0,
    status: 'published',
    level: 'beginner',
    language: 'vi',
    currency: 'VND',
    created_by: 0,
};

const formState = reactive({ ...initialFormState });

const rules: Record<string, Rule[]> = {
    title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
    price: [{ required: true, type: 'number', min: 0, message: 'Giá phải là một số không âm!' }],
};

const handleThumbnailChange = ({ fileList: newFileList }: UploadChangeParam) => {
    fileList.value = newFileList;
    if (newFileList.length > 0 && newFileList[0].originFileObj) {
        thumbnailFile.value = newFileList[0].originFileObj as File;
    } else {
        thumbnailFile.value = null;
    }
};

const handleFinish = async () => {
    try {
        await formRef.value.validate();
        loading.value = true;
        await courseApi.createCourse(formState, thumbnailFile.value);
        notification.success({ message: 'Tạo khóa học thành công!' });
        emit('finish');
        handleCancel();
    } catch (error: any) {
        notification.error({ message: error.message || 'Tạo khóa học thất bại.' });
    } finally {
        loading.value = false;
    }
};

const handleCancel = () => {
    formRef.value.resetFields();
    Object.assign(formState, initialFormState);
    thumbnailFile.value = null;
    fileList.value = [];
    emit('update:open', false);
};
</script>
