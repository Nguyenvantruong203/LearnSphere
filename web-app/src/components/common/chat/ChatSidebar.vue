<template>
    <div class="w-[320px] bg-white flex flex-col h-full border-r">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Tin nhắn</h2>
            <button @click="fetchThreads" class="text-gray-400 hover:text-gray-600 transition"
                title="Làm mới danh sách">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582M20 20v-5h-.581M5 9a9 9 0 0114 0M19 15a9 9 0 01-14 0" />
                </svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="border-b flex">
            <button v-for="tab in tabs" :key="tab.key" @click="changeTab(tab.key)"
                class="flex-1 text-center py-2 text-sm font-medium transition-all" :class="activeTab === tab.key
                    ? 'border-b-2 border-teal-500 text-teal-600'
                    : 'text-gray-500 hover:text-gray-700'">
                {{ tab.label }}
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex-1 flex items-center justify-center text-gray-400 text-sm">
            Đang tải danh sách...
        </div>

        <!-- Empty -->
        <div v-else-if="!threads.length"
            class="flex-1 flex flex-col items-center justify-center gap-2 text-gray-400 text-sm">
            <div>Không có cuộc trò chuyện nào</div>
        </div>

        <!-- List -->
        <div v-else class="flex-1 overflow-y-auto divide-y">
            <div v-for="thread in threads" :key="thread.id" @click="selectThread(thread)"
                class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-all"
                :class="{ 'bg-gradient-to-r from-teal-50 to-blue-50': activeThread?.id === thread.id }">

                <!-- Avatar -->
                <div
                    class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    <template v-if="thread.thread_type === 'course_group'">
                        <span class="text-sm font-semibold text-gray-600">👥</span>
                    </template>
                    <template v-else-if="thread.thread_type === 'private'">
                        <img v-if="getPartnerAvatar(thread)" :src="getPartnerAvatar(thread)"
                            class="w-full h-full object-cover" alt="Avatar" />
                        <span v-else>{{ getInitial(getPartnerName(thread)) }}</span>
                    </template>
                    <template v-else>
                        <span>🛠️</span>
                    </template>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">
                        {{
                            thread.thread_type === 'course_group'
                                ? (thread.course?.title || 'Thảo luận lớp học')
                                : getPartnerName(thread)
                        }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ thread.messages?.length ? thread.messages[0]?.message : 'Chưa có tin nhắn' }}
                    </p>
                </div>

                <!-- Time -->
                <div class="text-[11px] text-gray-400 whitespace-nowrap">
                    {{ formatTime(thread.updated_at) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import dayjs from 'dayjs'
import { chatApi } from '@/api/customer/chatApi'
import { chatApiInstructor } from '@/api/instructor/chatApiInstructor'

const props = defineProps<{
    currentUser?: { id: number; role?: 'student' | 'instructor' | 'admin' }
    courseId?: number
}>()

const emit = defineEmits(['select-thread'])

/** 🔹 Tabs động theo role */
const tabs = computed(() => {
    const role = props.currentUser?.role
    if (role === 'student')
        return [
            { key: 'course_group', label: 'Lớp học' },
            { key: 'private', label: 'Giảng viên' },
        ]
    if (role === 'instructor')
        return [
            { key: 'course_group', label: 'Lớp học tôi phụ trách' },
            { key: 'private', label: 'Học viên' },
            { key: 'support', label: 'Hỗ trợ (Admin)' },
        ]
    if (role === 'admin')
        return [{ key: 'support', label: 'Hỗ trợ người dùng' }]
    return []
})

const activeTab = ref('course_group')
const threads = ref<any[]>([])
const activeThread = ref<any>(null)
const loading = ref(false)

/** 🔹 Chọn API phù hợp theo role */
const api = computed(() => {
    return props.currentUser?.role === 'instructor' ? chatApiInstructor : chatApi
})

/** 🔹 Lấy danh sách thread theo tab hiện tại */
const fetchThreads = async () => {
    try {
        loading.value = true
        const res = await api.value.getThreads(activeTab.value as any, props.courseId)
        threads.value = Array.isArray(res) ? res : (res?.threads ?? [])
    } catch (err) {
        console.error('fetchThreads error:', err)
    } finally {
        loading.value = false
    }
}

/** 🔹 Đổi tab */
const changeTab = (key: string) => {
    activeTab.value = key
    fetchThreads()
}

/** 🔹 Chọn thread */
const selectThread = (thread: any) => {
    activeThread.value = thread
    emit('select-thread', thread)
}

/** 🔹 Helper functions */
const getPartnerName = (thread: any) => {
    if (!props.currentUser) return 'Cuộc trò chuyện'
    const partner = thread.participants?.find((p: any) => p.user.id !== props.currentUser.id)
    return partner?.user?.name || 'Người dùng'
}

const getPartnerAvatar = (thread: any) => {
    if (!props.currentUser) return '/images/avatar-default.png'
    const partner = thread.participants?.find((p: any) => p.user.id !== props.currentUser.id)
    return partner?.user?.avatar_url || '/images/avatar-default.png'
}

const getInitial = (text: string) => text.charAt(0).toUpperCase()
const formatTime = (time: string) => (time ? dayjs(time).format('HH:mm') : '')

/** 🔹 Khởi tạo */
onMounted(fetchThreads)
</script>

<style scoped>
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .1);
    border-radius: 9999px;
}
</style>
