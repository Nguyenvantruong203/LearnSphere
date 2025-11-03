<template>
    <a-drawer :open="visible" :width="900" title="Chi tiết giảng viên" @close="handleClose" class="instructor-drawer">
        <div v-if="user">
            <!-- 🎯 Trạng thái pending nổi bật -->
            <a-alert v-if="formState.role === 'instructor' && formState.status === 'pending'"
                message="Hồ sơ đang chờ phê duyệt"
                description="Vui lòng xem xét thông tin và quyết định phê duyệt hoặc từ chối hồ sơ này." type="warning"
                show-icon />

            <!-- 📊 Thông tin tổng quan -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-6">
                    <div
                        class="w-24 h-24 rounded-full bg-purple-200 flex items-center justify-center text-4xl font-bold text-purple-600">
                        {{ getInitials(formState.name) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ formState.name }}</h2>
                        <div class="flex flex-wrap gap-3 mb-3">
                            <a-tag :color="getRoleColor(formState.role)" class="text-sm px-3 py-1">
                                {{ getRoleLabel(formState.role) }}
                            </a-tag>
                            <a-tag :color="getStatusColor(formState.status)" class="text-sm px-3 py-1">
                                {{ getStatusLabel(formState.status) }}
                            </a-tag>
                            <a-tag v-if="formState.email_verified_at" color="green" class="text-sm px-3 py-1">
                                ✓ Email đã xác thực
                            </a-tag>
                            <a-tag v-else color="red" class="text-sm px-3 py-1">
                                ✗ Email chưa xác thực
                            </a-tag>
                        </div>
                        <div class="text-gray-600 space-y-1">
                            <div class="flex items-center gap-2">
                                <MailOutlined />
                                <a :href="`mailto:${formState.email}`" class="hover:text-purple-600">{{ formState.email
                                    }}</a>
                            </div>
                            <div v-if="formState.phone" class="flex items-center gap-2">
                                <PhoneOutlined />
                                {{ formState.phone }}
                            </div>
                            <div v-if="formState.teaching_experience" class="flex items-center gap-2">
                                <TrophyOutlined />
                                <span class="font-semibold text-purple-600">{{ formState.teaching_experience }}
                                    năm</span> kinh nghiệm giảng dạy
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📝 Form thông tin chi tiết -->
            <a-form layout="vertical" :model="formState" @finish="handleSave">
                <!-- Chuyên môn & Bio -->
                <a-card title="📚 Thông tin chuyên môn" class="mb-4">
                    <a-form-item label="Chuyên môn (Expertise)">
                        <a-input v-model:value="formState.expertise" :disabled="!isEditing"
                            placeholder="VD: Web Development, Data Science..." />
                    </a-form-item>

                    <a-form-item label="Tiểu sử (Bio)">
                        <a-textarea v-model:value="formState.bio" :disabled="!isEditing" :rows="5"
                            placeholder="Giới thiệu về bản thân, kinh nghiệm, thành tích..." />
                    </a-form-item>

                    <div class="grid grid-cols-2 gap-4">
                        <a-form-item label="🔗 LinkedIn URL">
                            <a-input v-model:value="formState.linkedin_url" :disabled="!isEditing">
                                <template #addonAfter>
                                    <a v-if="formState.linkedin_url" :href="formState.linkedin_url" target="_blank">
                                        <LinkOutlined />
                                    </a>
                                </template>
                            </a-input>
                        </a-form-item>

                        <a-form-item label="💼 Portfolio URL">
                            <a-input v-model:value="formState.portfolio_url" :disabled="!isEditing">
                                <template #addonAfter>
                                    <a v-if="formState.portfolio_url" :href="formState.portfolio_url" target="_blank">
                                        <LinkOutlined />
                                    </a>
                                </template>
                            </a-input>
                        </a-form-item>
                    </div>

                    <a-form-item label="Kinh nghiệm giảng dạy (năm)">
                        <a-input-number v-model:value="formState.teaching_experience" :disabled="!isEditing" :min="0"
                            :max="50" class="w-full" placeholder="0" />
                    </a-form-item>
                </a-card>

                <!-- Thông tin cá nhân -->
                <a-card title="👤 Thông tin cá nhân" class="mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <a-form-item label="Họ tên">
                            <a-input v-model:value="formState.name" :disabled="!isEditing" />
                        </a-form-item>

                        <a-form-item label="Username">
                            <a-input v-model:value="formState.username" :disabled="!isEditing" />
                        </a-form-item>

                        <a-form-item label="Email">
                            <a-input v-model:value="formState.email" disabled />
                        </a-form-item>

                        <a-form-item label="Số điện thoại">
                            <a-input v-model:value="formState.phone" :disabled="!isEditing" />
                        </a-form-item>

                        <a-form-item label="Ngày sinh">
                            <a-date-picker v-model:value="formState.birth_date" format="DD/MM/YYYY" class="w-full"
                                :disabled="!isEditing" />
                        </a-form-item>

                        <a-form-item label="Giới tính">
                            <a-select v-model:value="formState.gender" :disabled="!isEditing">
                                <a-select-option value="male">Nam</a-select-option>
                                <a-select-option value="female">Nữ</a-select-option>
                                <a-select-option value="other">Khác</a-select-option>
                            </a-select>
                        </a-form-item>
                    </div>

                    <a-form-item label="Địa chỉ">
                        <a-input v-model:value="formState.address" :disabled="!isEditing" />
                    </a-form-item>
                </a-card>

                <!-- ⚙️ Action Buttons -->
                <div class="sticky bottom-0 z-1000 bg-white border-t pt-4 -mx-6 px-6 pb-6">
                    <div class="flex justify-between items-center">
                        <!-- Nút trái -->
                        <div>
                            <a-button @click="handleClose" size="large">
                                Đóng
                            </a-button>
                        </div>

                        <!-- Nút phải -->
                        <div class="flex gap-3">
                            <template v-if="!isEditing">
                                <!-- Nếu đang pending, hiện nút duyệt/từ chối -->
                                <template v-if="formState.role === 'instructor' && formState.status === 'pending'">
                                    <a-button type="primary" danger size="large" @click="openRejectModal"
                                        :loading="loadingReject" class="flex items-center gap-2">
                                        <CloseCircleOutlined /> Từ chối
                                    </a-button>

                                    <a-button type="primary" size="large" @click="approveInstructor"
                                        :loading="loadingApprove"
                                        class="bg-green-500 hover:bg-green-600 border-green-500 flex items-center gap-2">
                                        <CheckCircleOutlined /> Phê duyệt
                                    </a-button>
                                </template>

                                <!-- Nếu không pending, chỉ hiện nút Edit -->
                                <a-button v-else size="large" @click="isEditing = true" class="flex items-center gap-2">
                                    <EditOutlined /> Chỉnh sửa
                                </a-button>
                            </template>

                            <!-- Khi đang edit -->
                            <template v-else>
                                <a-button size="large" @click="cancelEdit">Hủy</a-button>
                                <a-button type="primary" size="large" html-type="submit" :loading="loading">
                                    Lưu thay đổi
                                </a-button>
                            </template>
                        </div>
                    </div>
                </div>
            </a-form>
        </div>

        <!-- ❌ Modal nhập lý do từ chối -->
        <a-modal v-model:open="rejectModalVisible" title="Từ chối hồ sơ giảng viên" @ok="rejectInstructor"
            :confirmLoading="loadingReject" okText="Xác nhận từ chối" cancelText="Hủy" okButtonProps="{ danger: true }"
            width="600px">
            <a-alert message="Lưu ý" description="Giảng viên sẽ nhận được email thông báo kèm lý do từ chối."
                type="info" show-icon class="mb-4" />

            <a-form-item label="Lý do từ chối" required>
                <a-textarea v-model:value="rejectReason" :rows="6"
                    placeholder="Vui lòng nhập lý do từ chối cụ thể để giảng viên có thể hiểu và cải thiện hồ sơ..."
                    :maxlength="500" show-count />
            </a-form-item>
        </a-modal>
    </a-drawer>
</template>

<script setup lang="ts">
import { ref, reactive, watch, defineProps, defineEmits } from 'vue'
import { userApi } from '@/api/admin/userApi'
import { notification } from 'ant-design-vue'
import type { User } from '@/types/User'
import {
    EditOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    MailOutlined,
    PhoneOutlined,
    TrophyOutlined,
    LinkOutlined
} from '@ant-design/icons-vue'
import dayjs from 'dayjs'

const props = defineProps<{ visible: boolean; user: User | null }>()
const emit = defineEmits(['update:visible', 'refresh'])

const formState = reactive<Partial<User>>({})
const isEditing = ref(false)
const loading = ref(false)
const loadingApprove = ref(false)
const loadingReject = ref(false)
const rejectModalVisible = ref(false)
const rejectReason = ref('')

watch(
    () => props.user,
    (current) => {
        if (current) {
            Object.assign(formState, {
                ...current,
                birth_date: current.birth_date ? dayjs(current.birth_date) : null,
            })
        }
    },
    { immediate: true }
)

const getInitials = (name?: string) => {
    if (!name) return '?'
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2)
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'green'
        case 'pending': return 'orange'
        case 'rejected': return 'red'
        default: return 'gray'
    }
}

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'approved': return 'Đã phê duyệt'
        case 'pending': return 'Chờ duyệt'
        case 'rejected': return 'Đã từ chối'
        default: return status
    }
}

const getRoleColor = (role: string) => {
    switch (role) {
        case 'admin': return 'blue'
        case 'instructor': return 'purple'
        case 'student': return 'cyan'
        default: return 'gray'
    }
}

const getRoleLabel = (role: string) => {
    switch (role) {
        case 'admin': return 'Quản trị viên'
        case 'instructor': return 'Giảng viên'
        case 'student': return 'Học viên'
        default: return role
    }
}

const handleClose = () => {
    emit('update:visible', false)
    isEditing.value = false
    rejectReason.value = ''
}

const cancelEdit = () => {
    isEditing.value = false
    if (props.user) {
        Object.assign(formState, {
            ...props.user,
            birth_date: props.user.birth_date ? dayjs(props.user.birth_date) : null,
        })
    }
}

const handleSave = async () => {
    if (!props.user) return
    loading.value = true
    try {
        const payload = {
            ...formState,
            birth_date: formState.birth_date ? formState.birth_date.format('YYYY-MM-DD') : null,
        }
        await userApi.updateUser(props.user.id, payload)
        notification.success({
            message: 'Thành công!',
            description: 'Đã cập nhật thông tin giảng viên.'
        })
        emit('refresh')
        isEditing.value = false
    } catch (e: any) {
        notification.error({
            message: 'Lỗi!',
            description: e.message || 'Không thể cập nhật thông tin.'
        })
    } finally {
        loading.value = false
    }
}

// ✅ Phê duyệt instructor
const approveInstructor = async () => {
    if (!props.user) return
    loadingApprove.value = true
    try {
        await userApi.approveUser(props.user.id)
        notification.success({
            message: 'Phê duyệt thành công!',
            description: `${formState.name} đã được phê duyệt làm giảng viên.`
        })
        emit('refresh')
        handleClose()
    } catch (e: any) {
        notification.error({
            message: 'Lỗi!',
            description: e.message || 'Không thể phê duyệt giảng viên.'
        })
    } finally {
        loadingApprove.value = false
    }
}

// ❌ Từ chối instructor
const openRejectModal = () => {
    rejectReason.value = ''
    rejectModalVisible.value = true
}

const rejectInstructor = async () => {
    if (!props.user) return
    if (!rejectReason.value.trim()) {
        notification.warning({
            message: 'Thiếu thông tin',
            description: 'Vui lòng nhập lý do từ chối.'
        })
        return
    }
    loadingReject.value = true
    try {
        await userApi.rejectUser(props.user.id, rejectReason.value)
        notification.success({
            message: 'Đã từ chối!',
            description: `Hồ sơ của ${formState.name} đã bị từ chối.`
        })
        rejectModalVisible.value = false
        emit('refresh')
        handleClose()
    } catch (e: any) {
        notification.error({
            message: 'Lỗi!',
            description: e.message || 'Không thể từ chối hồ sơ.'
        })
    } finally {
        loadingReject.value = false
    }
}
</script>

<style scoped>
.instructor-drawer {
    padding: 24px 0 24px 24px;
}
.instructor-drawer :deep(.ant-card-head) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
}

.instructor-drawer :deep(.ant-card-head-title) {
    color: white;
    font-weight: 600;
}
</style>