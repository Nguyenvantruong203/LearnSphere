<template>
    <LayoutLoginAdmin title="Welcome Back" subtitle="Sign in with Admin account!"
        footer-text="Admin accounts are provided by the system administrator!" footer-link-text="Contact support"
        :show-footer="true">
        <Form layout="vertical" :model="formData" @finish="handleSubmit" class="space-y-6">
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
import LayoutLoginAdmin from '@/pages/customer/layout/layoutLoginAdmin.vue'
import { MailOutlined, LockOutlined } from '@ant-design/icons-vue';
import { authApi } from '@/api/authApi';

const router = useRouter()
const loading = ref(false)
const error = ref('')

const formData = reactive({
    email: '',
    password: ''
})

const emailRules = [
    { required: true, type: 'email', message: 'Vui lòng nhập email hợp lệ' }
]

const passwordRules = [
    { required: true, message: 'Vui lòng nhập mật khẩu' }
]

const handleSubmit = async (values: any) => {
    loading.value = true
    error.value = ''
    try {
        // Đảm bảo gửi đúng payload cho API
        const payload = { ...values, target: 'admin' };
        const response = await authApi.login(payload)

        // Đọc đúng key từ response của API
        const user = response.user;
        const token = response.access_token;

        if (!user || !token) {
            throw new Error('Phản hồi từ API không hợp lệ.');
        }

        if (user.role !== 'admin') {
            throw new Error('Tài khoản không có quyền truy cập trang quản trị.');
        }

        localStorage.setItem('auth_user', JSON.stringify(user))
        localStorage.setItem('auth_token', token)

        notification.success({
            message: 'Đăng nhập thành công',
            description: 'Chào mừng quản trị viên!',
            duration: 2
        })

        // Chuyển hướng đến trang quản lý người dùng
        router.push({ name: 'AdminListUsers' })

    } catch (err: any) {
        error.value = err.message || 'Email hoặc mật khẩu không chính xác.'
    } finally {
        loading.value = false
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
