<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Tin nhắn</h2>
                    <p class="text-xs text-white/80">{{ threads.length }} cuộc trò chuyện</p>
                </div>
            </div>
            <button @click="fetchThreads" 
                class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all duration-200 hover:scale-105"
                title="Làm mới danh sách">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582M20 20v-5h-.581M5 9a9 9 0 0114 0M19 15a9 9 0 01-14 0" />
                </svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="bg-white border-b border-gray-100 flex p-1 m-4 rounded-xl bg-gray-50">
            <button v-for="tab in tabs" :key="tab.key" @click="changeTab(tab.key)"
                class="flex-1 text-center py-2.5 px-3 text-sm font-medium transition-all duration-200 rounded-lg relative overflow-hidden"
                :class="activeTab === tab.key
                    ? 'bg-white text-indigo-600 shadow-sm font-semibold'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-white/50'">
                {{ tab.label }}
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex-1 flex flex-col items-center justify-center text-gray-400 p-8">
            <div class="animate-spin w-8 h-8 border-2 border-indigo-200 border-t-indigo-500 rounded-full mb-3"></div>
            <p class="text-sm">Đang tải danh sách...</p>
        </div>

        <!-- Empty -->
        <div v-else-if="!threads.length"
            class="flex-1 flex flex-col items-center justify-center gap-4 text-gray-400 p-8">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div class="text-center">
                <p class="font-medium text-gray-500 mb-1">Chưa có cuộc trò chuyện</p>
                <p class="text-xs text-gray-400">Tin nhắn sẽ xuất hiện ở đây</p>
            </div>
        </div>

        <!-- List -->
        <div v-else class="flex-1 overflow-y-auto">
            <div class="px-2 pb-2">
                <div v-for="thread in threads" :key="thread.id" @click="selectThread(thread)"
                    class="flex items-center gap-3 px-4 py-4 mb-2 cursor-pointer rounded-2xl transition-all duration-200 hover:shadow-md group"
                    :class="activeThread?.id === thread.id 
                        ? 'bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 shadow-lg' 
                        : 'hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 border-2 border-transparent'">

                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center shadow-sm"
                            :class="thread.thread_type === 'course_group' 
                                ? 'bg-gradient-to-br from-blue-400 to-indigo-500' 
                                : 'bg-gradient-to-br from-teal-400 to-cyan-500'">
                            <template v-if="thread.thread_type === 'course_group'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </template>
                            <template v-else-if="thread.thread_type === 'private'">
                                <img v-if="getPartnerAvatar(thread)" :src="getPartnerAvatar(thread)"
                                    class="w-full h-full object-cover" alt="Avatar" />
                                <div v-else class="w-full h-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                    {{ getInitial(getPartnerName(thread)) }}
                                </div>
                            </template>
                            <template v-else>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM12 18a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V18.75A.75.75 0 0112 18zM2.25 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3a.75.75 0 01-.75-.75zM18 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H18.75A.75.75 0 0118 12z" />
                                </svg>
                            </template>
                        </div>
                        <!-- Online indicator -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-bold text-gray-800 truncate"
                                :class="activeThread?.id === thread.id ? 'text-indigo-700' : 'group-hover:text-indigo-600'">
                                {{
                                    thread.thread_type === 'course_group'
                                        ? (thread.course?.title || 'Thảo luận lớp học')
                                        : getPartnerName(thread)
                                }}
                            </p>
                            <div class="text-[10px] text-gray-400 font-medium px-2 py-1 bg-gray-100 rounded-full">
                                <FormatTime :time="thread.updated_at" short class="text-[10px] text-gray-400 font-medium px-2 py-1 bg-gray-100 rounded-full" />
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 truncate flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01" />
                            </svg>
                            {{ thread.messages?.length ? thread.messages[0]?.message : 'Bắt đầu cuộc trò chuyện' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { chatApi } from '@/api/customer/chatApi'
import { chatApiInstructor } from '@/api/instructor/chatApiInstructor'
import FormatTime from '@/components/common/FormatTime.vue'

const props = defineProps<{
    currentUser?: { id: number; role?: 'student' | 'instructor' | 'admin' }
    courseId?: number
}>()

const emit = defineEmits(['select-thread'])

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
            { key: 'consult', label: 'Tư vấn học tập' },
        ]
    if (role === 'admin')
        return [
            { key: 'user_support', label: 'Người dùng' },
            { key: 'support', label: 'Hỗ trợ (Instructor)' },
        ]
    return []
})

const api = computed(() => {
    const role = props.currentUser?.role
    if (role === 'student') return chatApi
    if (role === 'instructor') return chatApiInstructor
    if (role === 'admin') return chatApiInstructor
    return chatApi
})

const activeTab = ref('course_group')
const threads = ref<any[]>([])
const activeThread = ref<any>(null)
const loading = ref(false)

/** 🔹 Lấy danh sách thread theo tab hiện tại */
const fetchThreads = async () => {
    try {
        loading.value = true

        let threadType = activeTab.value
        const role = props.currentUser?.role

        if (role === 'admin' && activeTab.value === 'private') {
            threadType = 'private'
        }

        const res = await api.value.getThreads(threadType as any, props.courseId)
        threads.value = Array.isArray(res) ? res : (res?.threads ?? [])
    } catch (err) {
        console.error('fetchThreads error:', err)
    } finally {
        loading.value = false
    }
}

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
    const partner = thread.participants?.find((p: any) => p.id !== props.currentUser.id)
    return partner?.name || 'Người dùng'
}

const getPartnerAvatar = (thread: any) => {
    if (!props.currentUser) return '/images/avatar-default.png'
    const partner = thread.participants?.find((p: any) => p.id !== props.currentUser.id)
    return partner?.avatar_url || '/images/avatar-default.png'
}


const getInitial = (text: string) => text.charAt(0).toUpperCase()

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
