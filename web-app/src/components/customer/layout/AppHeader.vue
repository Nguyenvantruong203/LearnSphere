<template>
  <header
    class="bg-white/95 backdrop-blur-lg shadow-lg border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
    <nav class="px-6 lg:px-20">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <div class="flex items-center">
          <div class="relative group">
            <img class="h-12 w-auto transition-transform duration-300 group-hover:scale-105"
              src="@/assets/images/logo.png" alt="LearnSphere Logo">
            <!-- Logo glow effect -->
            <div
              class="absolute -inset-2 bg-gradient-to-r from-teal-500/20 to-cyan-500/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
          </div>
        </div>

        <!-- Navigation Menu -->
        <div class="hidden md:block">
          <div class="flex items-center space-x-1">
            <router-link to="/"
              class="group relative px-4 py-2 font-medium text-base transition-all duration-300 rounded-xl"
              :class="isActive('/') ? 'text-teal-600' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
              Home
            </router-link>

            <router-link to="/courses"
              class="group relative px-4 py-2 font-medium text-base transition-all duration-300 rounded-xl"
              :class="isActive('/courses') ? 'text-teal-600' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
              Courses
            </router-link>

            <router-link to="/blog"
              class="group relative px-4 py-2 font-medium text-base transition-all duration-300 rounded-xl"
              :class="isActive('/blog') ? 'text-teal-600' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
              Blog
            </router-link>

            <router-link to="/about"
              class="group relative px-4 py-2 font-medium text-base transition-all duration-300 rounded-xl"
              :class="isActive('/about') ? 'text-teal-600' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
              About Us
            </router-link>
          </div>
        </div>

        <!-- User Actions -->
        <div class="flex items-center space-x-4">

          <!-- Notifications -->
          <NotificationDropdown v-if="authStore.isLoggedIn" role="student" />

          <!-- Shopping Cart -->
          <CartDropdown v-if="authStore.isLoggedIn" :userId="authStore.user.id" />

          <!-- User Profile / Auth Buttons -->
          <div class="flex items-center">
            <!-- Logged in user dropdown -->
            <div v-if="authStore.isLoggedIn && authStore.user && authStore.user.role !== 'admin'">
              <a-dropdown :trigger="['click']" placement="bottomRight">
                <a class="flex items-center space-x-3 cursor-pointer select-none group p-2 rounded-xl hover:bg-gray-50 transition-all duration-300"
                  @click.prevent>
                  <div class="relative">
                    <img
                      class="w-10 h-10 rounded-full object-cover shadow-md ring-2 ring-white group-hover:ring-teal-200 transition-all duration-300"
                      :src="authStore.user.avatar_url || defaultAvatar" alt="User Avatar" />
                    <div
                      class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                    </div>
                  </div>
                  <div class="hidden lg:block text-left">
                    <div class="text-sm font-semibold text-[#2F327D] group-hover:text-teal-600 transition-colors">
                      {{ authStore.user.name }}
                    </div>
                    <div class="text-xs text-[#696984]">Student</div>
                  </div>
                  <i
                    class="fas fa-chevron-down text-[#696984] text-sm group-hover:text-teal-600 group-hover:rotate-180 transition-all duration-300"></i>
                </a>

                <template #overlay>
                  <a-menu @click="onMenuClick" class="min-w-[200px] border-0 shadow-xl rounded-2xl overflow-hidden">
                    <a-menu-item key="profile">
                      <div class="flex items-center space-x-3 py-2">
                        <i class="fas fa-user-circle text-teal-600"></i>
                        <span>Profile</span>
                      </div>
                    </a-menu-item>
                    <a-menu-item key="courses">
                      <div class="flex items-center space-x-3 py-2">
                        <i class="fas fa-graduation-cap text-teal-600"></i>
                        <span>My Courses</span>
                      </div>
                    </a-menu-item>
                    <a-menu-item key="certificates">
                      <div class="flex items-center space-x-3 py-2">
                        <i class="fas fa-certificate text-teal-600"></i>
                        <span>My Certificates</span>
                      </div>
                    </a-menu-item>
                    <a-menu-divider />
                    <a-menu-item key="logout">
                      <div class="flex items-center space-x-3 py-2">
                        <i class="fas fa-sign-out-alt text-red-500"></i>
                        <span class="text-red-600">Logout</span>
                      </div>
                    </a-menu-item>
                  </a-menu>
                </template>
              </a-dropdown>
            </div>

            <!-- Not logged in - Auth buttons -->
            <div v-else class="flex items-center space-x-3">
              <router-link to="/login"
                class="px-6 py-2 text-[#696984] font-medium hover:text-teal-600 transition-all duration-300 rounded-xl hover:bg-gray-50"
                @click.native="goLogin">
                Login
              </router-link>

              <router-link to="/login"
                class="group px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                @click.native="goRegister">
                <span class="flex items-center space-x-2">
                  <span>Register</span>
                  <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </span>
              </router-link>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <button
            class="md:hidden flex items-center justify-center w-10 h-10 text-[#696984] hover:text-teal-600 hover:bg-gray-100 rounded-xl transition-all duration-300">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div class="md:hidden border-t border-gray-100 py-4 space-y-2">
        <router-link to="/" class="block px-4 py-3 font-semibold rounded-xl transition-all duration-300"
          :class="isActive('/') ? 'text-teal-600 bg-gradient-to-r from-teal-50 to-cyan-50' : 'text-[#2F327D] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
          Home
        </router-link>
        <router-link to="/courses" class="block px-4 py-3 font-medium rounded-xl transition-all duration-300"
          :class="isActive('/courses') ? 'text-teal-600 bg-gradient-to-r from-teal-50 to-cyan-50' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
          Courses
        </router-link>
        <router-link to="/blog" class="block px-4 py-3 font-medium rounded-xl transition-all duration-300"
          :class="isActive('/blog') ? 'text-teal-600 bg-gradient-to-r from-teal-50 to-cyan-50' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
          Blog
        </router-link>
        <router-link to="/about" class="block px-4 py-3 font-medium rounded-xl transition-all duration-300"
          :class="isActive('/about') ? 'text-teal-600 bg-gradient-to-r from-teal-50 to-cyan-50' : 'text-[#696984] hover:text-teal-600 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50'">
          About Us
        </router-link>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useRouter, useRoute } from 'vue-router'
import { notification } from 'ant-design-vue'
import type { MenuInfo } from 'ant-design-vue/es/menu/src/interface'
import type { CartItem } from '@/types/Order'
import { CartStorage } from '@/helpers/cartStorage'
import NotificationDropdown from '@/components/common/notification/NotificationDropdown.vue'

const authStore = useClientAuthStore()
const router = useRouter()
const route = useRoute()

// Cart state
const cartItems = ref<CartItem[]>([])

const defaultAvatar =
  'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'

// Helper: lấy userId từ localStorage
const getUserId = (): string | null => {
  const auth = JSON.parse(localStorage.getItem('client_auth') || '{}')
  return auth?.user?.id ? String(auth.user.id) : null
}

// Load cart items
const loadCartItems = () => {
  const userId = getUserId()
  if (!userId) {
    cartItems.value = []
    return
  }
  cartItems.value = CartStorage.getCart(userId).map((item: any) => ({
    ...item,
    price: Number(item.price) || 0,
  }))
}

// Listen for cart changes
const handleStorageChange = (e: StorageEvent) => {
  if (e.key === 'cart_data') {
    loadCartItems()
  }
}

onMounted(() => {
  loadCartItems()
  // Listen for localStorage changes
  window.addEventListener('storage', handleStorageChange)

  // Custom event khi cart update ở component khác
  window.addEventListener('cartUpdated', loadCartItems)
})

// Watch for route changes to reload cart
watch(() => route.path, () => {
  loadCartItems()
})

function goLogin() {
  localStorage.setItem('auth_tab', 'login')
  router.push('/login')
}

function goRegister() {
  localStorage.setItem('auth_tab', 'register')
  router.push('/login')
}

async function onMenuClick(info: MenuInfo) {
  const key = info.key as string
  if (key === 'logout') {
    await authStore.logout()
    notification.success({
      message: 'Logout Successful',
      description: 'See you next time!',
    })
  } else if (key === 'profile') {
    router.push('/profile/' + (authStore.user?.id || ''))
  } else if (key === 'courses') {
    router.push('/my-courses/' + (authStore.user?.id || ''))
  } else if (key === 'certificates') {
    router.push('/my-certificates/' + (authStore.user?.id || ''))
  } else if (key === 'settings') {
    router.push('/settings')
  }
}

function isActive(path: string) {
  return route.path === path
}
</script>
