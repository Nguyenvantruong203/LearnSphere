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

    <div class="flex h-[calc(100vh-80px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
      <!-- 🔹 Sidebar Chat -->
      <div class="w-[360px] flex-shrink-0">
        <ChatSidebar
          class="h-full shadow-xl bg-white/80 backdrop-blur-sm border-r border-gray-200/50"
          :current-user="currentUser"
          :role="currentUser?.role"
          @select-thread="handleSelectThread"
        />
      </div>

      <!-- 🔹 Chat Window -->
      <div class="flex-1 flex flex-col min-w-0">

          <div class="h-full rounded-2xl shadow-2xl overflow-hidden bg-white/90 backdrop-blur-sm border border-gray-200/50">
            <ChatWindow
              v-if="activeThread && currentUser"
              :thread-id="activeThread.id"
              :user="currentUser"
              class="h-full"
            />
            <div
              v-else
              class="h-full flex flex-col items-center justify-center text-gray-400"
            >
              <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-600 mb-2">Chào mừng đến với hệ thống chat</h3>
              <p class="text-sm text-gray-400 text-center max-w-sm">
                Chọn một cuộc trò chuyện từ danh sách bên trái để bắt đầu nhắn tin với học viên hoặc đồng nghiệp
              </p>
            </div>
          </div>
        </div>
    </div>
  </LayoutAdmin>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import LayoutAdmin from '@/pages/admin/layout/LayoutAdmin.vue'
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue'
import ChatSidebar from '@/components/common/chat/ChatSidebar.vue'
import ChatWindow from '@/components/common/chat/ChatWindow.vue'

function getCurrentAuth() {
  const admin = JSON.parse(localStorage.getItem('admin_auth') || '{}')
  const instructor = JSON.parse(localStorage.getItem('instructor_auth') || '{}')

  if (admin?.user?.role === 'admin') return admin
  if (instructor?.user?.role === 'instructor') return instructor

  return null
}

const authData = getCurrentAuth()
const currentUser = ref(authData?.user || null)
const activeThread = ref<any>(null)

const handleSelectThread = (thread: any) => {
  activeThread.value = thread
}

computed(() => currentUser.value?.role === 'admin')
computed(() => currentUser.value?.role === 'instructor')
</script>

<style scoped>
.bg-gray-50 {
  background-color: #f9fafb;
}

/* Responsive cho mobile */
@media (max-width: 1024px) {
  .w-\[360px\] {
    width: 280px;
  }
}

@media (max-width: 768px) {
  .w-\[360px\] {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 50;
    height: 100vh;
  }
  
  .flex-1 .p-4 {
    padding: 1rem;
  }
}
</style>
