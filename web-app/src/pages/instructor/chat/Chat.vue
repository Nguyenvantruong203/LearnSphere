<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item>
          <span class="text-gray-400">Pages</span>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span class="text-gray-700 font-bold">Tin nhắn</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <div class="flex h-[calc(100vh-64px)] bg-gray-50">
      <!-- 🔹 Sidebar Chat -->
      <ChatSidebar
        class="w-[340px] border-r"
        :current-user="currentUser"
        role="instructor"
        @select-thread="handleSelectThread"
      />

      <!-- 🔹 Chat Window -->
      <div class="flex-1 bg-white">
        <ChatWindow
          v-if="activeThread && currentUser"
          :thread-id="activeThread.id"
          :user="currentUser"
        />
        <div
          v-else
          class="h-full flex items-center justify-center text-gray-400 text-sm"
        >
          Chọn một cuộc trò chuyện để bắt đầu
        </div>
      </div>
    </div>
  </LayoutAdmin>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import LayoutAdmin from '@/pages/admin/layout/LayoutAdmin.vue'
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue'
import ChatSidebar from '@/components/common/chat/ChatSidebar.vue'
import ChatWindow from '@/components/common/chat/ChatWindow.vue'

/** 🧩 Lấy thông tin instructor từ localStorage */
const authData = JSON.parse(localStorage.getItem('admin_auth') || '{}')
const currentUser = ref(authData?.user || null)

/** 🧩 Thread đang chọn */
const activeThread = ref<any>(null)

/** 🔹 Khi chọn thread bên sidebar */
const handleSelectThread = (thread: any) => {
  activeThread.value = thread
}
</script>

<style scoped>
.bg-gray-50 {
  background-color: #f9fafb;
}
</style>
