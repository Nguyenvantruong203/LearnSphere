<template>
  <div class="fixed bottom-6 right-6 z-50">
    <!-- 💬 Nút mở/đóng chat -->
    <button
      @click="handleChatClick"
      class="bg-gradient-to-r from-teal-500 to-blue-500 text-white p-4 rounded-full shadow-lg hover:scale-105 transition-transform"
    >
      💬
    </button>

    <!-- 🪟 Khung chat -->
    <transition name="fade">
      <div
        v-if="isOpen"
        class="fixed bottom-20 right-6 w-96 h-[520px] bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden"
      >
        <ChatWindow
          v-if="threadId && currentUser"
          :thread-id="threadId"
          :user="currentUser"
          compact
          @close="isOpen = false"
        />
        <div
          v-else
          class="h-full flex items-center justify-center text-gray-400 text-sm"
        >
          Đang tải cuộc trò chuyện...
        </div>
      </div>
    </transition>

    <!-- ⚠️ Popup cảnh báo đăng nhập -->
    <transition name="fade">
      <div
        v-if="showLoginAlert"
        class="fixed bottom-28 right-6 w-80 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-sm"
      >
        <p class="text-gray-700 font-medium mb-2">
          ⚠️ Bạn cần đăng nhập để sử dụng tính năng chat.
        </p>
        <div class="flex justify-end gap-2">
          <button
            @click="showLoginAlert = false"
            class="px-3 py-1 text-gray-500 hover:text-gray-700"
          >
            Đóng
          </button>
          <button
            @click="redirectToLogin"
            class="px-3 py-1 bg-gradient-to-r from-teal-500 to-blue-500 text-white rounded-lg shadow-sm hover:opacity-90"
          >
            Đăng nhập
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import dayjs from 'dayjs'
import ChatWindow from '@/components/common/chat/ChatWindow.vue'
import { chatApi } from '@/api/customer/chatApi'
import echo from '@/utils/echo'

// ⚙️ Props để tái sử dụng cho cả trang home và trang course detail
interface Props {
  courseId?: number
  threadType?: 'user_support' | 'consult'
}
const props = defineProps<Props>()

// ⚙️ State
const router = useRouter()
const isOpen = ref(false)
const showLoginAlert = ref(false)
const threadId = ref<number | null>(null)
const messages = ref<any[]>([])
const currentUser = ref(JSON.parse(localStorage.getItem('client_auth') || '{}')?.user || null)

/** 🧠 Mở hoặc yêu cầu đăng nhập */
const handleChatClick = async () => {
  if (!currentUser.value) {
    showLoginAlert.value = true
    return
  }
  isOpen.value = !isOpen.value
}

/** 🧭 Điều hướng đăng nhập */
function redirectToLogin() {
  showLoginAlert.value = false
  router.push('/login')
}

/** 🚀 Khi user đã đăng nhập → tự động tạo thread phù hợp */
onMounted(async () => {
  if (!currentUser.value) return

  try {
    let res

    // Nếu có courseId → tư vấn khóa học
    if (props.threadType === 'consult' && props.courseId) {
      res = await chatApi.startConsult(props.courseId)
    } else {
      // Mặc định: hỗ trợ người dùng (home)
      res = await chatApi.startUserSupport()
    }

    threadId.value = res.thread.id
    initRealtime(res.thread.id)
  } catch (err) {
    console.error('❌ Chat init error:', err)
  }
})

/** 🧩 Theo dõi realtime bằng Laravel Echo */
function initRealtime(id: number) {
  echo.leave(`chat.thread.${id}`)

  echo.join(`chat.thread.${id}`)
    .listen('.message.sent', (event: any) => {
      if (event.sender.id !== currentUser.value?.id) {
        messages.value.push({
          ...event,
          sent_at: dayjs().format('YYYY-MM-DD HH:mm:ss'),
        })
      }
    })
    .error((e: any) => console.error('Echo error:', e))
}

watch(
  () => localStorage.getItem('client_auth'),
  (newVal) => {
    if (newVal) {
      currentUser.value = JSON.parse(newVal)?.user || null
    }
  }
)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
