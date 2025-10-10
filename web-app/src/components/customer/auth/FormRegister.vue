<template>
  <div class="w-full text-base">
    <Form layout="vertical" :model="formData" :rules="rules" @finish="handleFinish">
      <Alert v-if="error" :message="error" type="error" show-icon class="mb-4" />

      <!-- Fullname -->
      <FormItem class="mb-4" label="Fullname" name="name"
        :rules="[{ required: true, message: 'Vui lòng nhập họ tên' }]">
        <Input size="large" placeholder="Nhập họ và tên" v-model:value="formData.name" :disabled="loading" />
      </FormItem>

      <!-- Email -->
      <FormItem label="Email" name="email"
        :rules="[{ required: true, type: 'email', message: 'Vui lòng nhập email hợp lệ' }]">
        <Input size="large" placeholder="Nhập email" v-model:value="formData.email" :disabled="loading" />
      </FormItem>

      <!-- Password -->
      <FormItem label="Password" name="password" :rules="[{ required: true, message: 'Vui lòng nhập mật khẩu' }]">
        <InputPassword size="large" placeholder="Nhập mật khẩu" v-model:value="formData.password" :disabled="loading" />
      </FormItem>

      <!-- Confirm Password -->
      <FormItem label="Confirm Password" name="password_confirmation"
        :rules="[{ required: true, validator: validatePasswordConfirmation }]">
        <InputPassword size="large" placeholder="Xác nhận lại mật khẩu" v-model:value="formData.password_confirmation"
          :disabled="loading" />
      </FormItem>

      <!-- Submit -->
      <FormItem>
        <Button type="primary" html-type="submit" block size="large" :loading="loading"
          class="bg-green h-12 rounded-full">
          Register
        </Button>
      </FormItem>

      <!-- OR divider -->
      <Divider class="py-2">
        <span class="text-gray-500">or</span>
      </Divider>

      <!-- Google button -->
      <Button @click="handleGoogleLogin" :loading="loading" block size="large"
        class="!h-12 rounded-full mb-4 flex items-center justify-center gap-2">
        <GoogleOutlined />
        Continue with Google
      </Button>
    </Form>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Form, FormItem, Input, InputPassword, Button, Divider, Modal, Alert, notification } from 'ant-design-vue'
import { GoogleOutlined } from '@ant-design/icons-vue'
import { authApi } from '@/api/authApi'
import type { Rule } from 'ant-design-vue/es/form'

const router = useRouter()

const formData = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const loading = ref(false)
const error = ref<string | null>(null)

const validatePasswordConfirmation = async (_rule: Rule, value: string) => {
  if (!value) {
    return Promise.reject('Vui lòng xác nhận mật khẩu')
  }
  if (value !== formData.password) {
    return Promise.reject('Mật khẩu xác nhận không khớp!')
  }
  return Promise.resolve()
}

const rules = {
  password_confirmation: [{ required: true, validator: validatePasswordConfirmation }]
}

const handleFinish = async () => {
  loading.value = true
  error.value = null
  try {
    await authApi.register(formData)

    // Reset form
    formData.name = ''
    formData.email = ''
    formData.password = ''
    formData.password_confirmation = ''

    notification.success({
      message: 'Thành công',
      description: 'Đăng ký thành công!'
    })
    router.push('/login')
  } catch (err: any) {
    if (err.data && typeof err.data === 'object') {
      const errorMessages = Object.values(err.data).flat().join(' ');
      error.value = errorMessages || 'Đã có lỗi xảy ra. Vui lòng thử lại.';
    } else {
      error.value = err.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.';
    }
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
