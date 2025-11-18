<template>
  <a-modal title="Edit Course" :open="open" :confirm-loading="loading" :footer="null" @cancel="handleCancel"
    width="800px">
    <a-form v-if="formState" ref="formRef" :model="formState" :rules="rules" layout="vertical">
      <a-form-item label="Course Thumbnail" name="thumbnail">
        <a-upload v-model:file-list="fileList" list-type="picture-card" :before-upload="() => false"
          @change="handleThumbnailChange" @preview="handlePreview" :max-count="1">
          <div v-if="fileList.length < 1">
            <plus-outlined />
            <div style="margin-top: 8px">Upload</div>
          </div>
        </a-upload>
      </a-form-item>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="Title" name="title">
            <a-input v-model:value="formState.title" placeholder="Enter course title" />
          </a-form-item>
        </a-col>

        <a-col :span="6">
          <a-form-item label="Subject" name="subject">
            <a-select v-model:value="formState.subject" placeholder="Select subject" style="width: 100%" allowClear>
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
          <a-form-item label="Price" name="price">
            <a-input-number v-model:value="formState.price" style="width: 100%" :min="0"
              placeholder="Enter course price" :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
              :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-form-item label="Short Description" name="short_description">
        <a-textarea v-model:value="formState.short_description" :rows="3" placeholder="Enter a short description" />
      </a-form-item>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="Level" name="level">
            <a-select v-model:value="formState.level" placeholder="Select level">
              <a-select-option value="beginner">Beginner</a-select-option>
              <a-select-option value="intermediate">Intermediate</a-select-option>
              <a-select-option value="advanced">Advanced</a-select-option>
            </a-select>
          </a-form-item>
        </a-col>

        <a-col :span="12">
          <a-form-item label="Language" name="language">
            <a-select v-model:value="formState.language" placeholder="Select language">
              <a-select-option value="vi">Vietnamese</a-select-option>
              <a-select-option value="en">English</a-select-option>
            </a-select>
          </a-form-item>
        </a-col>
      </a-row>
      <div class="flex justify-end gap-3 mt-6">
        <a-button @click="handleCancel">Cancel</a-button>

        <a-button v-if="props.course?.status === 'rejected'" type="primary" @click="handleResubmit" :loading="loading">
          Gửi lại duyệt
        </a-button>

        <a-button v-else type="primary" :loading="loading" @click="handleFinish">
          Update
        </a-button>
      </div>
    </a-form>

    <a-skeleton v-else active />

    <a-modal :open="previewVisible" :title="previewTitle" :footer="null" @cancel="handlePreviewCancel">
      <img alt="Course thumbnail preview" style="width: 100%" :src="previewImage" />
    </a-modal>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { courseApi } from '@/api/instructor/courseApi'
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

const initialFormState: CoursePayload = {
  title: '',
  short_description: '',
  description: '',
  price: 0,
  status: 'draft',
  subject: 'it',
  level: 'beginner',
  language: 'en',
  currency: 'USD',
  created_by: 0,
}

const formState = reactive({ ...initialFormState })

watch(
  [() => props.open, () => props.course],
  async ([open, newCourse]) => {
    if (open && newCourse) {
      Object.assign(formState, {
        title: newCourse.title ?? '',
        short_description: newCourse.short_description ?? '',
        description: newCourse.description ?? '',
        price: newCourse.price ?? 0,
        status: newCourse.status ?? 'draft',
        subject: newCourse.subject ?? 'it',
        level: newCourse.level ?? 'beginner',
        language: newCourse.language ?? 'en',
        currency: (newCourse as any).currency ?? 'USD',
      })

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

    } else if (!open) {
      Object.assign(formState, initialFormState)
      fileList.value = []
      thumbnailFile.value = null
    }
  },
  { immediate: true }
)

const rules = {
  title: [{ required: true, message: 'Please enter the course title!' }],
  price: [
    { required: true, message: 'Please enter the course price!' },
    {
      validator: (_: any, value: any) => {
        if (value == null || value === '') return Promise.reject('Please enter the course price!')
        if (value < 0) return Promise.reject('Price must be a non-negative number!')
        return Promise.resolve()
      },
    },
  ],
}

const handleResubmit = async () => {
  if (!props.course) return
  try {
    loading.value = true

    await courseApi.resubmitCourse(props.course.id)

    notification.success({
      message: "Đã gửi lại yêu cầu duyệt!",
      description: "Admin sẽ kiểm tra khóa học của bạn."
    })

    emit('finish')
    handleCancel()

  } catch (e: any) {
    notification.error({
      message: "Gửi lại yêu cầu thất bại!",
      description: e.message
    })
  } finally {
    loading.value = false
  }
}

const handleThumbnailChange = ({ fileList: newFileList }: UploadChangeParam) => {
  fileList.value = newFileList
  thumbnailFile.value = newFileList[0]?.originFileObj || null
}

const handleFinish = async () => {
  if (!props.course) return
  try {
    await formRef.value?.validate()
    loading.value = true
    await courseApi.updateCourse(
      props.course.id,
      formState as CoursePayload,
      thumbnailFile.value ?? null
    )
    notification.success({ message: 'Course updated successfully!' })
    emit('finish')
    handleCancel()
  } catch (e: any) {
    notification.error({ message: e?.message || 'Failed to update course.' })
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  Object.assign(formState, initialFormState)
  fileList.value = []
  thumbnailFile.value = null
  emit('update:open', false)
}
</script>
