<template>
  <div class="flex items-center justify-center min-h-screen">
    <div class="text-center">
      <Spin size="large" />
      <p class="mt-4 text-lg">Đang xử lý đăng nhập, vui lòng chờ...</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Spin, Modal, notification } from 'ant-design-vue'
import { authApi } from '@/api/authApi'

const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const code = route.query.code as string

  if (!code) {
    notification.error({
      message: 'Lỗi',
      description: 'Không tìm thấy mã xác thực từ Google.'
    })
    router.push('/login') // Redirect to login page
    return
  }

  try {
    const { user, token } = await authApi.handleGoogleCallback(code)

    // Store user and token
    localStorage.setItem('auth_user', JSON.stringify(user))
    localStorage.setItem('auth_token', token)

    Modal.success({
      title: 'Thành công',
      content: 'Đăng nhập bằng Google thành công!',
      onOk() {
        // Redirect to homepage
        router.push('/')
      }
    })
  } catch (err: any) {
    // Kiểm tra nếu lỗi là do chờ duyệt (status 403)
    if (err.status === 403) {
      Modal.info({
        title: 'Chờ phê duyệt',
        content: err.message || 'Tài khoản của bạn đã được ghi nhận và đang chờ quản trị viên phê duyệt.',
        onOk() {
          router.push('/login')
        }
      })
    } else {
      // Xử lý các lỗi khác
      Modal.info({
        title: 'Chờ phê duyệt',
        content: err.message || 'Tài khoản của bạn đã được ghi nhận và đang chờ quản trị viên phê duyệt.',
        onOk() {
          router.push('/login')
        }
      })
      router.push('/login') // Redirect to login page
    }
  }
})
</script>
