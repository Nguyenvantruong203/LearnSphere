<template>
  <div class="p-14 w-full text-base">
    <Form layout="vertical" :model="formData" @finish="handleFinish" class="space-y-1">
      <Alert v-if="error" :message="error" type="error" show-icon class="mb-4" />

      <!-- Email -->
      <FormItem label="Email" name="email" :rules="[{ required: true, type: 'email', message: 'Vui lòng nhập email hợp lệ' }]">
        <Input size="large" placeholder="Nhập email" v-model:value="formData.email" :disabled="loading" />
      </FormItem>

      <!-- Password -->
      <FormItem label="Password" name="password" :rules="[{ required: true, message: 'Vui lòng nhập mật khẩu' }]">
        <InputPassword size="large" placeholder="Nhập mật khẩu" v-model:value="formData.password" :disabled="loading" />
      </FormItem>

      <Row justify="end" align="middle" class="my-2">
        <a type="link" class="my-2 text-black">
          Forgot password?
        </a>
      </Row>

      <!-- Submit -->
      <FormItem>
        <Button type="primary" html-type="submit" block size="large" :loading="loading" class="bg-green !h-12 rounded-full">
          Login
        </Button>
      </FormItem>

      <!-- OR divider -->
      <Divider class="py-2">
        <span class="text-gray-500">or</span>
      </Divider>

      <!-- Google button -->
      <Button @click="handleGoogleLogin" :loading="loading" block size="large" class="!h-12 rounded-full mb-4 flex items-center justify-center gap-2">
        <GoogleOutlined />
        Continue with Google
      </Button>
    </Form>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Form, FormItem, Input, InputPassword, Button, Row, Divider, Modal, notification, Alert } from 'ant-design-vue'
import { GoogleOutlined } from '@ant-design/icons-vue'
import { authApi } from '@/api/authApi'

const router = useRouter()

const formData = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref<string | null>(null)

const handleFinish = async () => {
  loading.value = true
  error.value = null
  try {
    const { user, token } = await authApi.login(formData)

    // Store user and token in localStorage
    localStorage.setItem('auth_user', JSON.stringify(user))
    localStorage.setItem('auth_token', token)

    Modal.success({
      title: 'Thành công',
      content: 'Đăng nhập thành công!',
      onOk() {
        // Redirect to homepage or dashboard
        router.push('/')
      }
    })
  } catch (err: any) {
    error.value = err.message || 'Email hoặc mật khẩu không chính xác.'
  } finally {
    loading.value = false
  }
}

const handleGoogleLogin = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await authApi.redirectToGoogle()
    window.location.href = response.url
  } catch (err: any) {
    error.value = err.message || 'Không thể kết nối tới dịch vụ của Google.'
    notification.error({
      message: 'Lỗi',
      description: error.value
    })
    loading.value = false
  }
}
</script>
