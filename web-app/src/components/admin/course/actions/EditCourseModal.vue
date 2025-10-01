<template>
  <a-modal title="Chỉnh sửa khóa học" :open="open" :confirm-loading="loading" ok-text="Cập nhật" cancel-text="Hủy"
    @ok="handleFinish" @cancel="handleCancel" width="800px">
    <a-form v-if="formState" ref="formRef" :model="formState" :rules="rules" layout="vertical">
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
            <a-select v-model:value="formState.subject" placeholder="Chọn chủ đề" style="width: 100%" allowClear>
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

    <a-skeleton v-else active />

    <!-- Preview -->
    <a-modal :open="previewVisible" :title="previewTitle" :footer="null" @cancel="handlePreviewCancel">
      <img alt="example" style="width: 100%" :src="previewImage" />
    </a-modal>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, nextTick } from 'vue'
import { courseApi } from '@/api/admin/courseApi'
import type { Course, CoursePayload } from '@/types/Course'
import { notification } from 'ant-design-vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import type { UploadChangeParam, UploadFile } from 'ant-design-vue'

interface Props {
  open: boolean
  course: Course | null
}
const props = defineProps<Props>()
const emit = defineEmits(['update:open', 'finish'])

const formRef = ref()
const loading = ref(false)
const thumbnailFile = ref<File | null>(null)
const fileList = ref<UploadFile[]>([])

// Preview
const previewVisible = ref(false)
const previewImage = ref('')
const previewTitle = ref('')
const handlePreviewCancel = () => (previewVisible.value = false)
const handlePreview = async (file: UploadFile) => {
  if (!file.url && !file.preview && file.originFileObj) {
    file.preview = await getBase64(file.originFileObj)
  }
  previewImage.value = file.url || (file.preview as string)
  previewTitle.value = file.name || ''
  previewVisible.value = true
}
function getBase64(file: File): Promise<string> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => resolve(reader.result as string)
    reader.onerror = err => reject(err)
  })
}

// Form state
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
}
const formState = reactive({ ...initialFormState })

// Watch for open & course
watch([() => props.open, () => props.course], async ([open, newCourse]) => {
  if (open && newCourse) {
    Object.assign(formState, {
      title: newCourse.title ?? '',
      short_description: newCourse.short_description ?? '',
      description: newCourse.description ?? '',
      price: newCourse.price ?? 0,
      status: newCourse.status ?? 'draft',
      visibility: newCourse.visibility ?? 'public',
      level: newCourse.level ?? 'beginner',
      language: newCourse.language ?? 'vn',
      currency: (newCourse as any).currency ?? 'VND',
    })

    // show thumbnail
    fileList.value = newCourse.thumbnail_url
      ? [
        {
          uid: '-1',
          name: 'thumbnail.png',
          status: 'done',
          url: newCourse.thumbnail_url,
        },
      ]
      : []

    await nextTick()
    formRef.value?.setFieldsValue(formState as any)
  } else if (!open) {
    Object.assign(formState, initialFormState)
    fileList.value = []
    thumbnailFile.value = null
  }
}, { immediate: true })

// Validation
const rules = {
  title: [{ required: true, message: 'Vui lòng nhập tiêu đề!' }],
  price: [
    { required: true, message: 'Vui lòng nhập giá!' },
    {
      validator: (_: any, value: any) => {
        if (value == null || value === '') return Promise.reject('Vui lòng nhập giá!')
        if (value < 0) return Promise.reject('Giá phải là số không âm!')
        return Promise.resolve()
      }
    }
  ],
}

// Upload
const handleThumbnailChange = ({ fileList: newFileList }: UploadChangeParam) => {
  fileList.value = newFileList
  thumbnailFile.value = newFileList[0]?.originFileObj || null
}

// Submit
const handleFinish = async () => {
  if (!props.course) return
  try {
    await formRef.value?.validate()
    loading.value = true
    await courseApi.updateCourse(props.course.id, formState as CoursePayload, thumbnailFile.value ?? null)
    notification.success({ message: 'Cập nhật khóa học thành công!' })
    emit('finish')
    handleCancel()
  } catch (e: any) {
    notification.error({ message: e?.message || 'Cập nhật khóa học thất bại.' })
  } finally {
    loading.value = false
  }
}

// Cancel
const handleCancel = () => {
  Object.assign(formState, initialFormState)
  fileList.value = []
  thumbnailFile.value = null
  emit('update:open', false)
}
</script>
