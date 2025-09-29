<template>
    <a-modal
        title="Chỉnh sửa khóa học"
        :open="open"
        :confirm-loading="loading"
        ok-text="Cập nhật"
        cancel-text="Hủy"
        @ok="handleFinish"
        @cancel="handleCancel"
        width="800px"
    >
        <a-form :key="formKey" v-if="formState" ref="formRef" :model="formState" :rules="rules" layout="vertical">
            <a-form-item label="Ảnh bìa" name="thumbnail">
                <a-upload
                    v-model:file-list="fileList"
                    list-type="picture-card"
                    :before-upload="() => false"
                    @change="handleThumbnailChange"
                    @preview="handlePreview"
                    :max-count="1"
                >
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
                <a-col :span="12">
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
                        <a-input v-model:value="formState.language" />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
         <a-skeleton v-else active />
    </a-modal>
    <a-modal :visible="previewVisible" :title="previewTitle" :footer="null" @cancel="handlePreviewCancel">
      <img alt="example" style="width: 100%" :src="previewImage" />
    </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, nextTick } from 'vue';
import { courseApi } from '@/api/admin/courseApi';
import type { Course, CoursePayload } from '@/types/Course';
import { notification } from 'ant-design-vue';
import type { Rule } from 'ant-design-vue/es/form';
import { PlusOutlined } from '@ant-design/icons-vue';
import type { UploadChangeParam, UploadFile } from 'ant-design-vue';

interface Props {
  open: boolean;
  course: Course | null;
}
const props = defineProps<Props>();
const emit = defineEmits(['update:open', 'finish']);

const formRef = ref();
const loading = ref(false);
const formKey = ref<string | number | undefined>(undefined);
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

// Initial form shape (same as CreateCourseModal)
const initialFormState: CoursePayload = {
  title: '',
  short_description: '',
  description: '',
  price: 0,
  status: 'draft',
  visibility: 'public',
  level: 'beginner',
  language: 'vi',
  currency: 'VND',
  created_by: 0,
};

const formState = reactive({ ...initialFormState });

 // Sync form when modal opens or course prop changes
watch([() => props.open, () => props.course], async ([open, newCourse]) => {
  if (open && newCourse) {
    // set form key so the form remounts when editing a different course while open
    // use a string key with timestamp to force remount even if same id/reference
    formKey.value = `${newCourse.id}-${Date.now()}`;

    // set fields into the reactive formState (preserve reactivity)
    Object.assign(formState, {
      title: newCourse.title ?? '',
      short_description: newCourse.short_description ?? '',
      description: newCourse.description ?? '',
      price: newCourse.price ?? 0,
      status: newCourse.status ?? 'draft',
      visibility: newCourse.visibility ?? 'public',
      level: newCourse.level ?? 'beginner',
      language: newCourse.language ?? 'vi',
      currency: (newCourse as any).currency ?? 'VND',
    });

    // show existing thumbnail
    if (newCourse.thumbnail_url) {
      fileList.value = [{
        uid: '-1',
        name: 'thumbnail.png',
        status: 'done',
        url: newCourse.thumbnail_url,
      }];
    } else {
      fileList.value = [];
    }

    // allow DOM to update then sync AntD form fields and reset validation state
    await nextTick();
    // ensure AntD form internal fields are updated when props.course changes
    formRef.value?.setFieldsValue(formState as any);
    formRef.value?.resetFields();
  } else if (!open) {
    // clear form when modal closed
    formKey.value = undefined;
    Object.assign(formState, initialFormState);
    fileList.value = [];
  }

  thumbnailFile.value = null;
}, { immediate: true });

const rules: Record<string, Rule[]> = {
  title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
  price: [{ required: true, message: 'Giá phải là một số không âm!' }],
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
  if (!props.course) return;

  try {
    await formRef.value.validate();
    loading.value = true;

    if (!props.course.slug) {
      notification.error({ message: 'Không tìm thấy slug khóa học.' });
      return;
    }

    // pass reactive object (formState) directly
    await courseApi.updateCourse(props.course.id, formState as CoursePayload, thumbnailFile.value ?? null);
    notification.success({ message: 'Cập nhật khóa học thành công!' });
    emit('finish');
    handleCancel();
  } catch (error: any) {
    notification.error({ message: error?.message || 'Cập nhật khóa học thất bại.' });
  } finally {
    loading.value = false;
  }
};

const handleCancel = async () => {
  formRef.value?.resetFields();
  formKey.value = undefined;
  Object.assign(formState, initialFormState);
  thumbnailFile.value = null;
  fileList.value = [];
  emit('update:open', false);
};
</script>
