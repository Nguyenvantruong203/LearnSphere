<template>
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Toggle Chat Button -->
    <button
      @click="handleChatClick"
      class="bg-gradient-to-r from-teal-500 to-blue-500 text-white p-4 rounded-full shadow-lg hover:scale-105 transition-transform"
    >
      
    </button>

    <!-- Chat Window -->
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
          Loading conversation...
        </div>
      </div>
    </transition>

    <!-- Login Required Alert -->
    <transition name="fade">
      <div
        v-if="showLoginAlert"
        class="fixed bottom-28 right-6 w-80 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-sm"
      >
        <p class="text-gray-700 font-medium mb-2">
          Warning: You need to log in to use the chat feature.
        </p>
        <div class="flex justify-end gap-2">
          <button
            @click="showLoginAlert = false"
            class="px-3 py-1 text-gray-500 hover:text-gray-700"
          >
            Close
          </button>
          <button
            @click="redirectToLogin"
            class="px-3 py-1 bg-gradient-to-r from-teal-500 to-blue-500 text-white rounded-lg shadow-sm hover:opacity-90"
          >
            Log In
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

// Props to make this component reusable (for homepage or course detail)
interface Props {
  courseId?: number
  threadType?: 'user_support' | 'consult'
}
const props = defineProps<Props>()

// State
const router = useRouter()
const isOpen = ref(false)
const showLoginAlert = ref(false)
const threadId = ref<number | null>(null)
const messages = ref<any[]>([])
const currentUser = ref(JSON.parse(localStorage.getItem('client_auth') || '{}')?.user || null)

/** Toggle chat or prompt login if not authenticated */
const handleChatClick = async () => {
  if (!currentUser.value) {
    showLoginAlert.value = true
    return
  }
  isOpen.value = !isOpen.value
}

/** Redirect to login page */
function redirectToLogin() {
  showLoginAlert.value = false
  router.push('/login')
}

/** On mount: Auto-create appropriate thread when user is logged in */
onMounted(async () => {
  if (!currentUser.value) return

  try {
    let res

    // If courseId is provided → course consultation chat
    if (props.threadType === 'consult' && props.courseId) {
      res = await chatApi.startConsult(props.courseId)
    } else {
      // Default: general user support (homepage)
      res = await chatApi.startUserSupport()
    }

    threadId.value = res.thread.id
    initRealtime(res.thread.id)
  } catch (err) {
    console.error('Chat initialization error:', err)
  }
})

/** Listen to real-time messages using Laravel Echo */
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

/** Watch for login state changes */
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