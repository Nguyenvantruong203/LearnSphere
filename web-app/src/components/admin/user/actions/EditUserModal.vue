<template>
    <a-modal :visible="visible" title="Chỉnh sửa người dùng" @cancel="handleCancel" :footer="null">
        <a-form :model="formState" @finish="handleFinish" layout="vertical">
            <a-form-item label="Họ và tên" name="name">
                <a-input v-model:value="formState.name" />
            </a-form-item>

            <a-form-item label="Email" name="email" :rules="[{ required: true, type: 'email', message: 'Email không hợp lệ!' }]">
                <a-input v-model:value="formState.email" />
            </a-form-item>

            <a-form-item label="Số điện thoại" name="phone">
                <a-input v-model:value="formState.phone" />
            </a-form-item>

            <a-form-item label="Địa chỉ" name="address">
                <a-input v-model:value="formState.address" />
            </a-form-item>

            <a-form-item label="Ngày sinh" name="birth_date">
                <a-date-picker v-model:value="formState.birth_date" class="w-full" format="YYYY-MM-DD" />
            </a-form-item>

            <a-form-item label="Giới tính" name="gender">
                <a-select v-model:value="formState.gender">
                    <a-select-option value="male">Nam</a-select-option>
                    <a-select-option value="female">Nữ</a-select-option>
                    <a-select-option value="other">Khác</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item label="Vai trò" name="role">
                <a-select v-model:value="formState.role">
                    <a-select-option value="student">Người dùng</a-select-option>
                    <a-select-option value="instructor">Giảng viên</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item label="Trạng thái" name="status">
                <a-select v-model:value="formState.status">
                    <a-select-option value="pending">Đang chờ</a-select-option>
                    <a-select-option value="approved">Đã duyệt</a-select-option>
                    <a-select-option value="rejected">Từ chối</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-button type="primary" html-type="submit" :loading="loading">Lưu thay đổi</a-button>
                <a-button style="margin-left: 10px" @click="handleCancel">Hủy</a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, defineProps, defineEmits } from 'vue';
import type { User } from '@/types/user';
import { userApi } from '@/api/userApi';
import { notification } from 'ant-design-vue';
import dayjs from 'dayjs';

const props = defineProps<{
    visible: boolean;
    user: User | null;
}>();

const emit = defineEmits(['update:visible', 'finish']);

const formState = reactive<Partial<User>>({});
const loading = ref(false);

watch(() => props.user, (currentUser) => {
    if (currentUser) {
        Object.assign(formState, {
            ...currentUser,
            birth_date: currentUser.birth_date ? dayjs(currentUser.birth_date) : null,
        });
    }
});

const handleFinish = async (values: any) => {
    if (!props.user) return;
    loading.value = true;
    try {
        const payload = {
            ...values,
            birth_date: values.birth_date ? values.birth_date.format('YYYY-MM-DD') : null,
        };
        await userApi.updateUser(props.user.id, payload);
        notification.success({ message: 'Cập nhật người dùng thành công!' });
        emit('finish');
        emit('update:visible', false);
    } catch (error: any) {
        notification.error({ message: error.message || 'Cập nhật thất bại.' });
    } finally {
        loading.value = false;
    }
};

const handleCancel = () => {
    emit('update:visible', false);
};
</script>
