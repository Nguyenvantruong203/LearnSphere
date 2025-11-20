<template>
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
      <a-result
        status="403"
        title="403"
        sub-title="Xin lỗi, bạn không có quyền truy cập trang này."
        class="bg-white p-8"
      >
        <template #icon>
          <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
              <LockOutlined class="text-4xl text-red-500" />
            </div>
          </div>
        </template>
        
        <template #extra>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a-button 
              type="primary" 
              size="large"
              @click="goBack"
              class="flex items-center gap-2"
            >
              <LeftOutlined />
              Quay lại
            </a-button>
            
            <router-link to="/login" v-if="!isAuthenticated">
              <a-button 
                type="default"
                size="large"
                class="flex items-center gap-2"
              >
                <UserOutlined />
                Đăng nhập
              </a-button>
            </router-link>
          </div>
        </template>
      </a-result>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { 
  LockOutlined, 
  LeftOutlined, 
  HomeOutlined, 
  UserOutlined 
} from '@ant-design/icons-vue'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useAdminAuthStore } from '@/stores/adminAuth'

const router = useRouter()
const clientAuth = useClientAuthStore()
const adminAuth = useAdminAuthStore()

// Check if user is authenticated in either client or admin
const isAuthenticated = computed(() => {
  return clientAuth.isLoggedIn || adminAuth.isLoggedIn
})

const goBack = () => {
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    router.push('/')
  }
}
</script>
