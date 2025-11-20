<template>
  <header class="sticky top-0 z-40">
    <div class="px-4">
      <div class="flex items-center justify-between">
        <!-- Left side - Logo/Brand -->
        <div class="flex items-center min-w-0">
          <slot />
        </div>

        <!-- Right side - User actions -->
        <div class="flex items-center space-x-2 lg:space-x-4">
          <div v-if="authStore.isLoggedIn && user" class="flex items-center space-x-2 lg:space-x-3">
            <!-- Notification Button -->
            <NotificationDropdown />

            <!-- User Dropdown -->
            <a-dropdown :trigger="['click']" placement="bottomRight">
              <a class="ant-dropdown-link flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200 cursor-pointer group"
                @click.prevent>
                <a-avatar :size="36" :src="user.avatar_url"
                  class="border-2 border-gray-200 group-hover:border-blue-300 transition-colors duration-200">
                  <template #icon>
                    <UserOutlined class="text-gray-500" />
                  </template>
                </a-avatar>
                <div class="hidden sm:block text-left">
                  <div class="font-semibold text-gray-800 text-sm leading-tight">{{ user.name }}</div>
                  <div class="text-xs text-gray-500">{{ user.email || 'Administrator' }}</div>
                </div>
                <DownOutlined
                  class="text-xs text-gray-400 ml-1 group-hover:text-gray-600 transition-colors duration-200" />
              </a>
              <template #overlay>
                <a-menu @click="handleMenuClick"
                  class="min-w-48 shadow-lg border border-gray-100 rounded-lg overflow-hidden">
                  <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="font-medium text-gray-800">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email || 'Administrator' }}</div>
                  </div>

                  <div class="space-y-2">
                    <a-menu-item key="profile" class="hover:bg-blue-50">
                      <template #icon>
                        <UserOutlined class="text-blue-600" />
                      </template>
                      <span class="text-gray-700">Profile</span>
                    </a-menu-item>

                    <a-menu-divider class="my-1" />

                    <a-menu-item key="logout" @click="handleLogout" class="hover:bg-red-50">
                      <template #icon>
                        <LogoutOutlined class="text-red-600" />
                      </template>
                      <span class="text-red-600">Logout</span>
                    </a-menu-item>
                  </div>
                </a-menu>
              </template>
            </a-dropdown>
          </div>

          <!-- Login button for non-authenticated users -->
          <div v-else>
            <router-link to="/admin/login">
              <a-button type="primary" size="large"
                class="font-medium px-6 hover:shadow-md transition-all duration-200">
                <LoginOutlined class="mr-2" />
                Login
              </a-button>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import {
  UserOutlined,
  LogoutOutlined,
  DownOutlined,
  LoginOutlined,
} from '@ant-design/icons-vue'
import { useAdminAuthStore } from '@/stores/adminAuth'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'
import { notification } from 'ant-design-vue'
import NotificationDropdown from '@/components/common/notification/NotificationDropdown.vue'

const authStore = useAdminAuthStore()
const { user } = storeToRefs(authStore)
const router = useRouter()

const handleMenuClick = (info: { key: string | number }) => {
  switch (info.key) {
    case 'profile':
      router.push('/admin/profile')
      break
    case 'settings':
      router.push('/admin/settings')
      break
    default:
      break
  }
}

const handleNotificationClick = () => {
  notification.info({
    message: 'Notifications',
    description: 'You have 3 new notifications.',
    placement: 'topRight',
  })
}

const handleLogout = async () => {
  try {
    await authStore.logout()
    notification.success({
      message: 'Logout Successful',
      description: 'See you next time!',
    })
    router.push('/admin/login')
  } catch (error) {
    console.error('Logout failed:', error)
    notification.error({
      message: 'Logout Failed',
      description: 'An error occurred while logging out.',
    })
  }
}
</script>
