<template>
  <header class="bg-white shadow-sm">
    <nav class="px-4 sm:px-6 lg:px-10">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <img class="h-10 w-auto sticky z-50 top-0" src="@/assets/images/logo.png" alt="LearnSphere Logo">
        </div>

        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <a href="/" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Home</a>
            <a href="/courses" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">Courses</a>
            <a href="/blog" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">Blog</a>
            <a href="/about" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">About Us</a>
          </div>
        </div>

        <!-- User Profile -->
        <div class="flex items-center">
          <div v-if="authStore.isLoggedIn && authStore.user" class="relative">
            <a-dropdown :trigger="['click']">
              <a class="ant-dropdown-link flex items-center space-x-2 cursor-pointer select-none" @click.prevent>
                <img class="h-8 w-8 rounded-full"
                  :src="authStore.user.avatar || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'"
                  alt="User" />
                <span class="text-sm text-gray-700">{{ authStore.user.name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </a>

              <template #overlay>
                <a-menu @click="onMenuClick">
                  <a-menu-item key="profile">Profile</a-menu-item>
                  <a-menu-item key="settings">Settings</a-menu-item>
                  <a-menu-divider />
                  <a-menu-item key="logout">Logout</a-menu-item>
                </a-menu>
              </template>
            </a-dropdown>
          </div>
          <div v-else class="flex items-center space-x-2">
            <a href="/login" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">Đăng nhập</a>
            <a href="/login"
              class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">Đăng
              ký</a>
          </div>
        </div>

      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import type { MenuInfo } from 'ant-design-vue/es/menu/src/interface';

const authStore = useAuthStore();
const router = useRouter();

async function onMenuClick(info: MenuInfo) {
  const key = info.key as string;
  if (key === 'logout') {
    await authStore.logout();
  } else if (key === 'profile') {
    router.push('/profile');
  } else if (key === 'settings') {
    router.push('/settings');
  }
}
</script>
