<template>
    <a-modal v-model:open="open" title="Thêm bài học mới" ok-text="Thêm" cancel-text="Hủy" @ok="handleFinish"
        @cancel="handleCancel" :confirm-loading="loading">

        <div v-if="youtubeConnected">
            <a-form :model="form" :rules="rules" ref="formRef" layout="vertical">
                <a-form-item label="Tiêu đề" name="title" required>
                    <a-input v-model:value="form.title" placeholder="Nhập tiêu đề bài học" />
                </a-form-item>
                <a-form-item label="Nội dung" name="content">
                    <a-textarea v-model:value="form.content" placeholder="Nhập nội dung bài học" rows="4" />
                </a-form-item>
                <a-form-item label="Thứ tự" name="order">
                    <a-input-number v-model:value="form.order" min="1" />
                </a-form-item>
                <a-form-item label="Tải lên video" name="file">
                    <a-upload v-model:file-list="fileList" :before-upload="beforeUpload" :max-count="1"
                        accept="video/mp4,video/quicktime,video/x-matroska" list-type="text">
                        <a-button>Chọn video</a-button>
                    </a-upload>
                </a-form-item>
            </a-form>
        </div>

        <div v-else class="text-center space-y-3">
            <p>Bạn cần kết nối YouTube để tải video lên.</p>
            <a-button type="primary" @click="connectYoutube">Kết nối YouTube</a-button>
        </div>
    </a-modal>
</template>

<script setup lang="ts">
import { ref, computed, defineProps, defineEmits } from 'vue'
import { notification } from 'ant-design-vue'
import { lessonApi } from '@/api/admin/lessonApi'
import { youtubeApi } from '@/api/admin/youtubeApi'

const props = defineProps<{
    open: boolean
    topicId: number | undefined
    youtubeConnected: boolean
}>()

const emit = defineEmits<{
    (e: 'update:open', v: boolean): void
    (e: 'finish', data: { topicId: number | undefined, courseId?: any }): void
}>()

const open = computed({
    get: () => props.open,
    set: (val: boolean) => emit('update:open', val),
})

const youtubeConnected = computed(() => props.youtubeConnected)

const form = ref({
    title: '',
    content: '',
    order: 1,
    file: null as File | null,
})

const fileList = ref<any[]>([])
const loading = ref(false)
const formRef = ref()

const rules = {
    title: [{ required: true, message: 'Tiêu đề không được để trống', trigger: 'blur', type: 'string' }],
    order: [{ required: true, type: 'number', min: 1, message: 'Thứ tự phải lớn hơn 0', trigger: 'blur' }],
    file: [{ required: true, message: 'Vui lòng tải lên video', trigger: 'change', type: 'object' }],
}

const beforeUpload = (file: File) => {
    form.value.file = file
    fileList.value = [file as any]
    return false
}

const resetForm = () => {
    form.value = { title: '', content: '', order: 1, file: null }
    fileList.value = []
    formRef.value?.resetFields()
}

const handleFinish = async () => {
    try {
        await formRef.value?.validate()
        loading.value = true

        const formData = new FormData()
        formData.append('title', form.value.title)
        formData.append('content', form.value.content || '')
        formData.append('order', String(form.value.order))
        if (form.value.file) formData.append('file', form.value.file)

        await lessonApi.createLesson(props.topicId as number, formData)
        notification.success({ message: 'Thêm bài học thành công!' })
        emit('finish', { topicId: props.topicId, courseId: (props as any).courseId })
        open.value = false
        resetForm()
    } catch (err: any) {
        notification.error({ message: err.message || 'Thêm bài học thất bại.' })
    } finally {
        loading.value = false
    }
}

const handleCancel = () => {
    resetForm()
    open.value = false
}

const auth = JSON.parse(localStorage.getItem('admin_auth') || '{}')
const userId = auth.user?.id

const connectYoutube = () => {
    window.location.href = youtubeApi.connectUrl(userId)
}
</script>
