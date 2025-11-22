<template>
  <div class="relative" @mouseenter="openDropdown" @mouseleave="closeDropdown">
    <!-- Bell Icon -->
    <button class="relative flex items-center justify-center w-10 h-10 
           text-[#696984] hover:text-teal-600 hover:bg-gray-100 
           rounded-xl transition-all duration-300">
      <i class="fas fa-bell text-lg"></i>

      <!-- Badge FIXED -->
      <div v-if="unreadCount > 0" class="absolute -top-1 -right-1 min-w-[18px] h-[18px]
                bg-gradient-to-r from-orange-500 to-red-500 text-white
                text-xs rounded-full flex items-center justify-center font-semibold
                pointer-events-none select-none">
        {{ unreadCount }}
      </div>
    </button>

    <!-- DROPDOWN -->
    <transition name="fade-slide">
      <div v-if="open" class="notification-dropdown bg-white rounded-2xl shadow-2xl border-0 overflow-hidden"
        @mouseenter="cancelClose" @mouseleave="closeDropdown">

        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 
                            border-b border-gray-100 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>

          <p class="cursor-pointer text-sm text-teal-600 hover:text-teal-800 font-medium" @click.stop="goToList">
            See all →
          </p>
        </div>

        <!-- List -->
        <div class="max-h-80 overflow-y-auto">
          <div v-if="notifications.length === 0" class="p-6 text-center text-gray-500">
            <i class="fas fa-bell-slash text-4xl mb-3"></i>
            <p>No notifications yet.</p>
          </div>

          <div v-else>
            <div v-for="item in notifications" :key="item.id" class="flex gap-3 px-6 py-4 border-b last:border-b-0 
                                    hover:bg-gray-50 transition cursor-pointer" @click="handleClick(item)">

              <!-- Icon -->
              <div class="w-10 h-10 flex items-center justify-center bg-teal-100 
                                        text-teal-600 rounded-xl">
                <i class="fas fa-bell"></i>
              </div>

              <div class="flex-1">
                <p class="font-medium text-gray-800">{{ item.title }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ item.message }}</p>
                <p class="text-xs text-gray-400 mt-1">
                  {{ formatDate(item.created_at) }}
                </p>
              </div>

              <div v-if="!item.read_at" class="w-3 h-3 bg-red-500 rounded-full self-center"></div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3 bg-gray-50 text-center border-t border-gray-100">
          <button class="text-sm text-teal-600 hover:text-teal-800 font-medium" @click="markAllAsRead">
            Mark all as read
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"
import { notification } from "ant-design-vue"
import { notificationApi } from "@/api/notificationApi"
import echo from "@/utils/echo"

const router = useRouter()

const open = ref(false)
let closeTimer = null

const notifications = ref([])
const unreadCount = ref(0)

/** Open dropdown */
const openDropdown = () => {
  clearTimeout(closeTimer)
  open.value = true
  loadNotifications()
}

const closeDropdown = () => {
  closeTimer = setTimeout(() => {
    open.value = false
  }, 200)
}

const cancelClose = () => clearTimeout(closeTimer)

/** Load top 3 notifications */
const loadNotifications = async () => {
  const res = await notificationApi.getNotifications(1, 10)
  const list = res.notifications ?? []

  notifications.value = list.slice(0, 3)
  unreadCount.value = list.filter(n => !n.read_at).length
}

/** Format date */
const formatDate = (date) =>
  new Date(date).toLocaleString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    month: "short",
    day: "2-digit",
  })

/** CLICK item → mark as read + redirect */
const handleClick = async (item) => {
  if (!item.read_at) {
    await notificationApi.markAsRead(item.id)
    item.read_at = new Date().toISOString()
    unreadCount.value = Math.max(0, unreadCount.value - 1)
  }

  if (item.data?.url) {
    router.push(item.data.url)
  }
}

/** Mark all */
const markAllAsRead = async () => {
  await notificationApi.markAllAsRead()
  notifications.value.forEach(n => (n.read_at = true))
  unreadCount.value = 0
  notification.success({ message: "Marked all as read" })
}

const goToList = () => {
  router.push("/notifications")
  open.value = false
}

/** Realtime */
onMounted(() => {
  loadNotifications()

  const auth =
    JSON.parse(localStorage.getItem("client_auth") || "{}") ||
    JSON.parse(localStorage.getItem("admin_auth") || "{}") ||
    JSON.parse(localStorage.getItem("instructor_auth") || "{}")

  const userId = auth?.user?.id
  if (!userId) return

  echo.private(`notifications.${userId}`)
    .listen(".notification.created", (e) => {
      const noti = e.notificationUser.notification
      notifications.value.unshift(noti)
      unreadCount.value++
    })
})
</script>


<style scoped>
.notification-dropdown {
  position: fixed;
  top: 70px;
  right: 150px;
  width: 360px;
  max-height: 500px;
  z-index: 9999;
}

/* Animations */
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.15s ease;
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
