<template>
  <div class="flex flex-col h-full bg-white overflow-hidden">
    <div v-if="!compact"
      class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
        </div>
        <div>
          <h3 class="font-bold text-lg truncate">
            {{ thread?.course?.title || thread?.title || 'Discussion' }}
          </h3>
          <p class="text-xs text-white/80">
            {{ thread?.thread_type === 'course_group' ? 'Group Discussion' : 'Private Chat' }}
          </p>
        </div>
      </div>
      <button @click="$emit('close')"
        class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all duration-200 hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Messages Area -->
    <div ref="chatBox" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gradient-to-b from-gray-50 to-white">
      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center py-12 text-gray-400">
        <div class="animate-spin w-8 h-8 border-2 border-indigo-200 border-t-indigo-500 rounded-full mb-3"></div>
        <p class="text-sm">Loading messages...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="!orderedMessages.length" class="flex flex-col items-center justify-center py-16 text-gray-400">
        <div
          class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
        </div>
        <p class="font-medium text-gray-500 mb-2">No messages yet</p>
        <p class="text-xs text-gray-400 text-center">Start the conversation by sending the first message</p>
      </div>

      <!-- Messages -->
      <div v-for="msg in orderedMessages" :key="msg.id" class="flex items-start gap-3 group"
        :class="isOwnMessage(msg) ? 'justify-end' : 'justify-start'">
        <!-- Sender Avatar (only for others) -->
        <div v-if="!isOwnMessage(msg)" class="flex-shrink-0">
          <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm bg-gradient-to-br from-gray-200 to-gray-300">
            <img :src="msg.sender?.avatar_url || '/images/avatar-default.png'" class="w-full h-full object-cover"
              alt="avatar" />
          </div>
        </div>

        <!-- Message Content -->
        <div class="flex flex-col max-w-[70%] min-w-0">
          <!-- Sender name in group chat -->
          <div v-if="thread?.thread_type === 'course_group' && !isOwnMessage(msg)"
            class="text-xs font-medium text-indigo-600 mb-1 px-1">
            {{ msg.sender?.name }}
          </div>

          <!-- Message Bubble -->
          <div
            class="relative px-4 py-3 rounded-2xl shadow-sm backdrop-blur-sm transition-all duration-200 group-hover:shadow-md"
            :class="isOwnMessage(msg)
              ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-br-md'
              : 'bg-white/90 border border-gray-200/50 text-gray-800 rounded-bl-md'">
            <!-- Message Text -->
            <div class="whitespace-pre-wrap break-words text-sm leading-relaxed">
              {{ msg.message }}
            </div>

            <!-- Timestamp -->
            <div class="text-[10px] mt-2 flex items-center gap-1"
              :class="isOwnMessage(msg) ? 'text-white/70 justify-end' : 'text-gray-400 justify-start'">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <FormatTime :time="msg.sent_at" format="HH:mm" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div class="border-t border-gray-200/50 bg-white/80 backdrop-blur-sm p-4">
      <div class="flex items-center gap-3"> 
        <button
          class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>

        <textarea v-model="newMessage" placeholder="Type your message..."
          class="flex-1 resize-none overflow-hidden rounded-lg border border-gray-200 px-4 py-2 pr-12 text-sm placeholder-gray-400 outline-none transition-all duration-200 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50"
          rows="1" @keydown.enter.prevent="handleEnterKey" @input="autoResize" />

        <button @click="sendMessage" :disabled="!newMessage.trim() || newMessage.length > 500"
          class="w-10 h-10 rounded-2xl bg-client text-white flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, watch, computed } from 'vue'
import { chatApi } from '@/api/customer/chatApi'
import { chatApiInstructor } from '@/api/instructor/chatApiInstructor'
import FormatTime from '@/components/common/FormatTime.vue'
import echo from '@/utils/echo'

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

/** Unified API based on user role */
const api = computed(() => {
  const role = props.user?.role
  if (role === 'student') return chatApi
  return chatApiInstructor
})

const orderedMessages = computed(() =>
  [...messages.value].sort((a, b) => new Date(a.sent_at).getTime() - new Date(b.sent_at).getTime())
)

const isOwnMessage = (msg: any) => msg?.sender?.id === props.user.id

/** Load messages for the current thread */
const loadMessages = async () => {
  try {
    loading.value = true
    const res = await api.value.getMessages(props.threadId)
    thread.value = res.thread
    messages.value = res.messages || []
    scrollToBottom()
  } catch (err: any) {
    console.error('loadMessages error:', err)
    if (err?.response?.status === 401) {
      alert('Your session has expired. Please log in again.')
    }
  } finally {
    loading.value = false
  }
}

/** Send a new message */
const sendMessage = async () => {
  if (!newMessage.value.trim()) return
  try {
    const msg = await api.value.sendMessage(props.threadId, newMessage.value)
    messages.value.push(msg)
    newMessage.value = ''
    scrollToBottom()
    emit('refresh-sidebar')
  } catch (err: any) {
    console.error('sendMessage error:', err)
    if (err?.response?.status === 401) {
      alert('Your session has expired. Please log in again.')
    }
  }
}

/** Scroll to the bottom of the chat */
const scrollToBottom = () => {
  nextTick(() => {
    if (chatBox.value) chatBox.value.scrollTop = chatBox.value.scrollHeight
  })
}

/** Handle Enter key (send message, Shift+Enter for new line) */
const handleEnterKey = (event: KeyboardEvent) => {
  if (event.shiftKey) return
  event.preventDefault()
  sendMessage()
}

/** Auto-resize textarea based on content */
const autoResize = (event: Event) => {
  const textarea = event.target as HTMLTextAreaElement
  textarea.style.height = 'auto'
  textarea.style.height = `${Math.min(textarea.scrollHeight, 128)}px`
}

/** Listen to real-time incoming messages */
const initRealtime = () => {
  echo.join(`chat.thread.${props.threadId}`).listen('.message.sent', (event: any) => {
    if (event.sender.id !== props.user.id) {
      messages.value.push(event)
      scrollToBottom()
    }
  })
}

/** Reload messages when thread changes */
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