<template>
    <a-modal 
        :visible="visible" 
        title="Edit User" 
        @cancel="handleCancel" 
        :footer="null"
    >
        <a-form 
            :model="formState" 
            @finish="handleFinish" 
            layout="vertical"
        >
            <a-form-item label="Full Name" name="name">
                <a-input v-model:value="formState.name" />
            </a-form-item>

            <a-form-item 
                label="Email" 
                name="email" 
                :rules="[{ required: true, type: 'email', message: 'Invalid email address!' }]"
            >
                <a-input v-model:value="formState.email" />
            </a-form-item>

            <a-form-item label="Phone Number" name="phone">
                <a-input v-model:value="formState.phone" />
            </a-form-item>

            <a-form-item label="Address" name="address">
                <a-input v-model:value="formState.address" />
            </a-form-item>

            <a-form-item label="Date of Birth" name="birth_date">
                <a-date-picker 
                    v-model:value="formState.birth_date" 
                    class="w-full" 
                    format="YYYY-MM-DD" 
                />
            </a-form-item>

            <a-form-item label="Gender" name="gender">
                <a-select v-model:value="formState.gender">
                    <a-select-option value="male">Male</a-select-option>
                    <a-select-option value="female">Female</a-select-option>
                    <a-select-option value="other">Other</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item label="Role" name="role">
                <a-select v-model:value="formState.role">
                    <a-select-option value="student">User</a-select-option>
                    <a-select-option value="instructor">Instructor</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item label="Status" name="status">
                <a-select v-model:value="formState.status">
                    <a-select-option value="pending">Pending</a-select-option>
                    <a-select-option value="approved">Approved</a-select-option>
                    <a-select-option value="rejected">Rejected</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-button type="primary" html-type="submit" :loading="loading">
                    Save Changes
                </a-button>
                <a-button style="margin-left: 10px" @click="handleCancel">
                    Cancel
                </a-button>
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, defineProps, defineEmits } from 'vue';
import type { User } from '@/types/User';
import { userApi } from '@/api/admin/userApi';
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
        notification.success({ message: 'User updated successfully!' });
        emit('finish');
        emit('update:visible', false);
    } catch (error: any) {
        notification.error({ message: error.message || 'Update failed.' });
    } finally {
        loading.value = false;
    }
};

const handleCancel = () => {
    emit('update:visible', false);
};
</script>
