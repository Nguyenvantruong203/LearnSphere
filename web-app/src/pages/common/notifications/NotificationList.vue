<template>
  <section
    class="max-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-6 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">

      <!-- Back Button -->
      <button @click="$router.back()"
        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-gray-700 mb-6">
        <i class="fas fa-arrow-left text-sm"></i>
        <span>Back</span>
      </button>

      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-6">
          <div class="flex items-center gap-4">
            <div
              class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Notifications
              </h1>
              <p class="text-gray-600">Latest updates from the system</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-3">
            <a-button v-if="unreadCount > 0" type="default" size="large" @click="markAllAsRead"
              :loading="markingAllRead" class="rounded-xl">
              <template #icon>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                </svg>
              </template>
              Mark All as Read
            </a-button>

            <a-select v-model:value="filterType" size="large" class="w-48" placeholder="Filter notifications">
              <a-select-option value="all">All</a-select-option>
              <a-select-option value="unread">Unread</a-select-option>
              <a-select-option value="read">Read</a-select-option>
            </a-select>
          </div>
        </div>

        <!-- Stats Bar -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
          <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21" />
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-800">{{ pagination.total || 0 }}</p>
                <p class="text-sm text-gray-600">Total Notifications</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.3" />
                  <circle cx="12" cy="12" r="5" fill="currentColor" />
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-800">{{ unreadCount }}</p>
                <p class="text-sm text-gray-600">Unread</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                </svg>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-800">{{ readCount }}</p>
                <p class="text-sm text-gray-600">Read</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block">
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full animate-spin mb-6 relative">
            <div class="absolute inset-2 bg-white rounded-full"></div>
          </div>
          <p class="text-gray-500 text-lg animate-pulse">Loading notifications...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!notifications || notifications.length === 0" class="text-center py-20">
        <div
          class="w-32 h-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full mx-auto flex items-center justify-center mb-8">
          <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M20 17H4V10C4 7.87 5.84 6.11 8.14 6.01C8.68 4.83 9.81 4 11.11 4C12.41 4 13.54 4.83 14.08 6.01C16.16 6.11 18 7.87 18 10H20C20.55 10 21 10.45 21 11S20.55 12 20 12H19V17C19 17.55 18.55 18 18 18S17 17.55 17 17V12H16V17H4C3.45 17 3 16.55 3 16V10C3 6.69 5.69 4 9 4H11.11C13.84 4 16.11 5.69 16.89 8.01C18.16 8.28 20 9.22 20 11.01V17Z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-700 mb-4">
          {{ filterType === 'unread' ? 'No unread notifications' : filterType === 'read' ? 'No read notifications' : 'No notifications yet' }}
        </h3>
        <p class="text-gray-500 text-lg max-w-md mx-auto">
          {{ filterType === 'all'
            ? 'You will receive notifications when there are new updates from the system.'
            : 'Try changing the filter to see other notifications.'
          }}
        </p>
      </div>

      <!-- Notifications List -->
      <div v-else class="space-y-4 h-[calc(100vh-360px)] overflow-y-auto pr-2">
        <div v-for="(item, index) in filteredNotifications" :key="item.id"
          class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300 cursor-pointer animate-fade-in"
          :style="{ animationDelay: `${index * 60}ms` }"
          :class="{ 'bg-blue-50/60 border-blue-200 shadow-lg': !item.read_at }" @click="handleClick(item)">
          <div class="flex items-start gap-5">
            <!-- Icon -->
            <div
              class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                <path fill="currentColor"
                  d="M5 19q-.425 0-.712-.288T4 18t.288-.712T5 17h1v-7q0-2.075 1.25-3.687T10.5 4.2v-.7q0-.625.438-1.062T12 2t1.063.438T13.5 3.5v.7q2 .5 3.25 2.113T18 10v7h1q.425 0 .713.288T20 18t-.288.713T19 19zm7 3q-.825 0-1.412-.587T10 20h4q0 .825-.587 1.413T12 22" />
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between mb-2">
                <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition-colors">
                  {{ item.title }}
                </h3>
                <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                  <div v-if="!item.read_at" class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                  <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-lg whitespace-nowrap">
                    {{ getRelativeTime(item.created_at) }}
                  </span>
                </div>
              </div>

              <p class="text-gray-600 text-sm leading-relaxed mt-1">{{ item.message }}</p>

              <div class="flex items-center justify-between mt-4 text-xs text-gray-500">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3H19M5,5V7H19V5H5M5,9V11H19V9H5M5,13V15H19V13H5M5,17V19H19V17H5Z" />
                  </svg>
                  {{ formatDate(item.created_at) }}
                </span>

                <span v-if="item.read_at" class="flex items-center gap-1 text-green-600 font-medium">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                  </svg>
                  Read
                </span>
              </div>
            </div>

            <!-- Arrow -->
            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-3 flex justify-center">
        <a-pagination v-model:current="page" :pageSize="perPage" :total="pagination.total" :show-size-changer="false"
          :show-quick-jumper="true" :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} notifications`"
          @change="fetchList" class="custom-pagination" />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import { useRouter } from "vue-router"
import { notification } from 'ant-design-vue'
import { notificationApi } from "@/api/notificationApi"

const router = useRouter()

const notifications = ref([])
const page = ref(1)
const perPage = ref(10)
const pagination = ref({ total: 0 })
const filterType = ref('all')
const markingAllRead = ref(false)
const loading = ref(false)

// Stats
const unreadCount = computed(() => notifications.value.filter(n => !n.read_at).length)
const readCount = computed(() => notifications.value.filter(n => n.read_at).length)

// Filter
const filteredNotifications = computed(() => {
  if (filterType.value === 'unread') return notifications.value.filter(n => !n.read_at)
  if (filterType.value === 'read') return notifications.value.filter(n => n.read_at)
  return notifications.value
})

// Date formatting
const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getRelativeTime = (date) => {
  if (!date) return ''
  const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' })
  const now = Date.now()
  const diff = now - new Date(date)
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)

  if (minutes < 1) return 'just now'
  if (minutes < 60) return rtf.format(-minutes, 'minute')
  if (hours < 24) return rtf.format(-hours, 'hour')
  if (days < 7) return rtf.format(-days, 'day')
  return formatDate(date)
}

const fetchList = async () => {
  loading.value = true
  try {
    const res = await notificationApi.getNotifications(page.value, perPage.value)
    notifications.value = Array.isArray(res.notifications) ? res.notifications : []
    pagination.value = res.pagination || { total: 0 }
  } catch (error) {
    notification.error({
      message: 'Error loading data',
      description: 'Could not load notifications. Please try again.',
    })
  } finally {
    {
      loading.value = false
    }
  }
}

const markAllAsRead = async () => {
  markingAllRead.value = true
  try {
    await notificationApi.markAllAsRead()
    notifications.value.forEach(n => {
      if (!n.read_at) n.read_at = new Date().toISOString()
    })
    notification.success({
      message: 'Success',
      description: 'All notifications marked as read.',
    })
  } catch {
    notification.error({
      message: 'Error',
      description: 'Failed to mark notifications as read.',
    })
  } finally {
    markingAllRead.value = false
  }
}

const handleClick = async (item) => {
  try {
    if (!item.read_at) {
      await notificationApi.markAsRead(item.id)
      item.read_at = new Date().toISOString()
    }
    if (item.data?.url) {
      router.push(item.data.url)
    }
  } catch {
    notification.error({
      message: 'Error',
      description: 'Could not update notification status.',
    })
  }
}

onMounted(fetchList)
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out forwards;
  opacity: 0;
}

.custom-pagination :deep(.ant-pagination-item) {
  border-radius: 10px !important;
}

.custom-pagination :deep(.ant-pagination-item-active) {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  border-color: transparent;
}

.custom-pagination :deep(.ant-pagination-item-active a) {
  color: white;
}
</style>