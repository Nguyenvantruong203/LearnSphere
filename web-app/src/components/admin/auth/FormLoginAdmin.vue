<template>
    <LayoutLoginAdmin title="Welcome Back" subtitle="Sign in with Admin account!"
        footer-text="Admin accounts are provided by the system administrator!" footer-link-text="Contact support"
        :show-footer="true">
        <Form layout="vertical" :model="formData" @finish="handleFinish" class="space-y-6">
            <Alert v-if="error" :message="error" type="error" show-icon class="mb-6" />

            <!-- Email Field -->
            <FormItem name="email" :rules="emailRules">
                <div class="relative">
                    <Input size="large" placeholder="Email address" v-model:value="formData.email" :disabled="loading"
                        class="auth-input" />
                    <MailOutlined class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                </div>
            </FormItem>

            <!-- Password Field -->
            <FormItem name="password" :rules="passwordRules">
                <div class="relative">
                    <Input size="large" type="password" placeholder="Password" v-model:value="formData.password"
                        :disabled="loading" class="auth-input" />
                    <LockOutlined class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                </div>
            </FormItem>

            <!-- Submit Button -->
            <FormItem>
                <Button type="primary" html-type="submit" block size="large" :loading="loading"
                    class="auth-button mt-4">
                    <span v-if="!loading">Sign In</span>
                </Button>
            </FormItem>
        </Form>
    </LayoutLoginAdmin>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Form, FormItem, Input, Button, notification, Alert } from 'ant-design-vue'
import type { Rule } from 'ant-design-vue/es/form'
import LayoutLoginAdmin from '@/pages/customer/layout/layoutLoginAdmin.vue'
import { MailOutlined, LockOutlined } from '@ant-design/icons-vue';
import { useAuthStore } from '@/stores/auth';

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(false)
const error = ref('')

const formData = reactive({
    email: '',
    password: ''
})

const emailRules: Rule[] = [
    { required: true, type: 'email', message: 'Vui lòng nhập email hợp lệ' }
]

const passwordRules: Rule[] = [
    { required: true, message: 'Vui lòng nhập mật khẩu' }
]

const handleFinish = async (values: any) => {
    loading.value = true
    error.value = ''
    try {
        const payload = { ...values, target: 'admin' };
        const loggedInUser = await authStore.login(payload);

        if (loggedInUser.role !== 'admin') {
            await authStore.logout();
            throw new Error('Tài khoản không có quyền truy cập trang quản trị.');
        }

        notification.success({
            message: 'Đăng nhập thành công',
            description: 'Chào mừng quản trị viên!',
            duration: 2
        });

        router.push({ name: 'AdminListUsers' });

    } catch (err: any) {
        error.value = err.message || 'Email hoặc mật khẩu không chính xác.';
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped>
:deep(.ant-input-password .anticon) {
    color: #5CE1CA !important;
}

:deep(.ant-form-item-label > label) {
    color: #5CE1CA !important;
    font-weight: 500;
}

:deep(.ant-input:focus),
:deep(.ant-input-password:focus) {
    box-shadow: 0 0 0 2px rgba(92, 225, 202, 0.4) !important;
}
</style>
