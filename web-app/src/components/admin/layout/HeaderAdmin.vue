<template>
  <header class="p-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-center"> 
          <slot />
      </div>
      <div class="flex items-center space-x-4">
        <div v-if="authStore.isLoggedIn && user" class="flex items-center space-x-3">
          <a-dropdown :trigger="['click']">
            <a class="ant-dropdown-link flex items-center" @click.prevent>
              <a-avatar :src="user.avatar_url">
                <template #icon><UserOutlined /></template>
              </a-avatar>
              <span class="ml-2 font-semibold text-gray-700">{{ user.name }}</span>
            </a>
            <template #overlay>
              <a-menu @click="handleMenuClick">
                <a-menu-item key="profile">
                  <template #icon><UserOutlined /></template>
                  Trang cá nhân
                </a-menu-item>
                <a-menu-divider />
                <a-menu-item key="logout" @click="handleLogout">
                  <template #icon>
                    <LogoutOutlined />
                  </template>
                  Đăng xuất
                </a-menu-item>
              </a-menu>
            </template>
          </a-dropdown>

          <a-button type="text" size="small">
            <span class="flex items-center justify-center">
              <BellOutlined />
            </span>
          </a-button>
        </div>
        <div v-else>
          <router-link to="/admin/login">
            <a-button type="primary">
              Đăng nhập
            </a-button>
          </router-link>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import {
  BellOutlined,
  UserOutlined,
  LogoutOutlined
} from '@ant-design/icons-vue'
import { useAdminAuthStore } from '@/stores/adminAuth'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

const authStore = useAdminAuthStore()
const { user } = storeToRefs(authStore)
const router = useRouter()

const handleMenuClick = (info: { key: string | number }) => {
  if (info.key === 'profile') {
    router.push('/admin/profile');
  }
};

const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/admin/login')
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>
