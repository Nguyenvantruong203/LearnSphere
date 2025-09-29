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
import { useClientAuthStore } from '@/stores/clientAuth'

const route = useRoute()
const router = useRouter()
const authStore = useClientAuthStore()

onMounted(async () => {
  const code = route.query.code as string

  if (!code) {
    notification.error({
      message: 'Lỗi',
      description: 'Không tìm thấy mã xác thực từ Google.'
    })
    router.push('/login')
    return
  }

  try {
    await authStore.loginWithGoogle(code)

    Modal.success({
      title: 'Thành công',
      content: 'Đăng nhập bằng Google thành công!',
      onOk() {
        router.push('/')
      }
    })
  } catch (err: any) {
    if (err.status === 403) {
      Modal.info({
        title: 'Chờ phê duyệt',
        content: err.message || 'Tài khoản đang chờ quản trị viên phê duyệt.',
        onOk() {
          router.push('/login')
        }
      })
    } else {
      notification.error({
        message: 'Lỗi',
        description: err.message || 'Đăng nhập Google thất bại.'
      })
      router.push('/login')
    }
  }
})
</script>
