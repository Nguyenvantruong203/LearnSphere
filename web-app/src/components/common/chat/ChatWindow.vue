<template>
  <div class="flex flex-col h-full bg-white rounded-2xl overflow-hidden shadow-sm">
    <!-- 🔹 Header -->
    <div v-if="!compact" class="flex items-center justify-between px-4 py-3 border-b bg-white">
      <div class="font-semibold text-gray-800 truncate">
        {{ thread?.course?.title || thread?.title || 'Thảo luận' }}
      </div>
      <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- 🔹 Messages -->
    <div ref="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
      <div v-if="loading" class="text-center text-gray-400 text-sm">Đang tải tin nhắn...</div>

      <!-- 💬 Tin nhắn -->
      <div
        v-for="msg in orderedMessages"
        :key="msg.id"
        class="flex items-start"
        :class="isOwnMessage(msg) ? 'justify-end' : 'justify-start'"
      >
        <!-- 🧑 Avatar -->
        <div v-if="!isOwnMessage(msg)" class="flex-shrink-0 mr-2">
          <img
            :src="msg.sender?.avatar_url || '/images/avatar-default.png'"
            class="w-8 h-8 rounded-full object-cover"
            alt="avatar"
          />
        </div>

        <!-- Nội dung -->
        <div
          class="flex flex-col max-w-[75%]"
          :class="isOwnMessage(msg) ? 'items-end text-right' : 'items-start text-left'"
        >
          <!-- 👤 Tên người gửi -->
          <div
            v-if="thread?.thread_type === 'course_group' && !isOwnMessage(msg)"
            class="text-xs text-gray-500 mb-0.5"
          >
            {{ msg.sender?.name }}
          </div>

          <!-- 💬 Nội dung tin -->
          <div
            class="inline-block px-4 py-2 rounded-2xl shadow-sm whitespace-pre-wrap break-words"
            :class="isOwnMessage(msg)
              ? 'bg-gradient-to-r from-teal-500 to-blue-500 text-white'
              : 'bg-white border text-gray-800'"
          >
            <div>{{ msg.message }}</div>
            <div
              class="text-[11px] mt-1"
              :class="isOwnMessage(msg) ? 'text-gray-200 text-right' : 'text-gray-400 text-right'"
            >
              {{ formatTime(msg.sent_at) }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 🔹 Input -->
    <div class="border-t bg-white p-3 flex items-center gap-2">
      <input
        v-model="newMessage"
        placeholder="Nhập tin nhắn..."
        class="flex-1 border rounded-xl px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-teal-500"
        @keyup.enter="sendMessage"
      />
      <button
        @click="sendMessage"
        class="bg-gradient-to-r from-teal-500 to-blue-500 text-white px-4 py-2 rounded-xl hover:opacity-90 transition-all"
      >
        Gửi
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, watch, computed } from 'vue'
import { chatApi } from '@/api/customer/chatApi'
import { chatApiInstructor } from '@/api/instructor/chatApiInstructor'
import echo from '@/utils/echo'
import dayjs from 'dayjs'

const props = defineProps<{
  threadId: number
  user: { id: number; name?: string; avatar_url?: string; role?: 'student' | 'instructor' | 'admin' }
  compact?: boolean
}>()

const emit = defineEmits(['close', 'refresh-sidebar'])

const thread = ref<any>(null)
const messages = ref<any[]>([])
const newMessage = ref('')
const loading = ref(false)
const chatBox = ref<HTMLDivElement | null>(null)

/** 🔹 Chọn API theo role */
const api = computed(() => (props.user?.role === 'instructor' ? chatApiInstructor : chatApi))

/** 🔹 Sắp xếp tin nhắn cũ → mới */
const orderedMessages = computed(() => {
  return [...messages.value].sort(
    (a, b) => new Date(a.sent_at).getTime() - new Date(b.sent_at).getTime()
  )
})

const isOwnMessage = (msg: any) => msg?.sender?.id === props.user.id

/** 🔹 Load tin nhắn */
const loadMessages = async () => {
  try {
    loading.value = true
    const res = await api.value.getMessages(props.threadId)
    thread.value = res.thread
    messages.value = res.messages || []
    scrollToBottom()
  } catch (err) {
    console.error('loadMessages error:', err)
  } finally {
    loading.value = false
  }
}

/** 🔹 Gửi tin nhắn */
const sendMessage = async () => {
  if (!newMessage.value.trim()) return
  try {
    const msg = await api.value.sendMessage(props.threadId, newMessage.value)
    messages.value.push(msg)
    newMessage.value = ''
    scrollToBottom()
  } catch (err) {
    console.error('sendMessage error:', err)
  }
}

/** 🔹 Tự động scroll xuống cuối */
const scrollToBottom = () => {
  nextTick(() => {
    if (chatBox.value) chatBox.value.scrollTop = chatBox.value.scrollHeight
  })
}

/** 🔹 Format thời gian */
const formatTime = (time: string) => dayjs(time).format('HH:mm')

/** 🔹 Realtime với Echo */
const initRealtime = () => {
  echo.join(`chat.thread.${props.threadId}`).listen('.message.sent', (event: any) => {
    console.log('📩 Realtime received:', event)
    if (event.sender.id !== props.user.id) {
      messages.value.push(event)
      scrollToBottom()
    }
  })
}

/** 🔹 Khi đổi threadId thì reload */
watch(
  () => props.threadId,
  async () => {
    await loadMessages()
    initRealtime()
  }
)

onMounted(async () => {
  await loadMessages()
  initRealtime()
})
</script>

<style scoped>
.bg-gray-50 {
  background-color: #f9fafb;
}
</style>
