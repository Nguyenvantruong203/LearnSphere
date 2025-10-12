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
          <button v-if="authStore.isLoggedIn"
            class="hidden md:flex items-center justify-center w-10 h-10 text-[#696984] hover:text-teal-600 hover:bg-gray-100 rounded-xl transition-all duration-300 group relative">
            <i class="fas fa-bell group-hover:scale-110 transition-transform duration-300"></i>
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full">
            </div>
          </button>

          <!-- Shopping Cart -->
          <a-dropdown :trigger="['click']" placement="bottomRight" v-if="authStore.isLoggedIn">
            <button
              class="hidden md:flex items-center justify-center w-10 h-10 text-[#696984] hover:text-teal-600 hover:bg-gray-100 rounded-xl transition-all duration-300 group relative">
              <i class="fas fa-shopping-cart group-hover:scale-110 transition-transform duration-300"></i>
              <div v-if="cartItemsCount > 0"
                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-xs rounded-full flex items-center justify-center font-semibold">
                {{ cartItemsCount }}
              </div>
            </button>

            <template #overlay>
              <div class="bg-white rounded-2xl shadow-2xl border-0 overflow-hidden"
                style="width: 380px; max-height: 500px;">
                <!-- Cart Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-100">
                  <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Giỏ hàng</h3>
                    <span class="text-sm text-gray-600">{{ cartItemsCount }} khóa học</span>
                  </div>
                </div>

                <!-- Cart Items -->
                <div class="max-h-80 overflow-y-auto">
                  <div v-if="cartItems.length === 0" class="p-8 text-center">
                    <div class="text-gray-400 mb-3">
                      <i class="fas fa-shopping-cart text-4xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Giỏ hàng của bạn đang trống</p>
                    <router-link to="/courses"
                      class="inline-flex items-center px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                      Khám phá khóa học
                    </router-link>
                  </div>

                  <div v-else class="py-2">
                    <div v-for="item in cartItems" :key="item.id"
                      class="flex items-center gap-3 px-6 py-3 hover:bg-gray-50 transition-colors">
                      <div class="relative flex-shrink-0">
                        <img :src="item.thumbnail_url" :alt="item.title" class="w-16 h-12 object-cover rounded-lg" />
                        <div class="absolute inset-0 flex items-center justify-center">
                          <div class="w-6 h-6 bg-white bg-opacity-80 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M8 5v10l7-5-7-5z" />
                            </svg>
                          </div>
                        </div>
                      </div>

                      <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ item.title }}</h4>
                        <FormatPrice :price="item.price" class="text-sm text-teal-600 font-semibold mt-1" />
                      </div>

                      <button @click="removeFromCart(item.id)"
                        class="flex-shrink-0 p-1 text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Cart Footer -->
                <div v-if="cartItems.length > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                  <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Tổng cộng:</span>
                    <FormatPrice :price="cartTotal" class="text-lg font-bold text-teal-600" />
                  </div>
                  <router-link to="/cart"
                    class="block w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-center font-semibold py-3 rounded-xl hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105">
                    Xem chi tiết giỏ hàng
                  </router-link>
                </div>
              </div>
            </template>
          </a-dropdown>

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
                    <a-menu-item key="settings">
                      <div class="flex items-center space-x-3 py-2">
                        <i class="fas fa-cog text-teal-600"></i>
                        <span>Settings</span>
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
import { ref, computed, onMounted, watch } from 'vue'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useRouter, useRoute } from 'vue-router'
import { notification } from 'ant-design-vue'
import type { MenuInfo } from 'ant-design-vue/es/menu/src/interface'
import type { CartItem } from '@/types/Order'
import { CartStorage } from '@/helpers/cartStorage'

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

// Cart computed values
const cartItemsCount = computed(() => cartItems.value.length)

const cartTotal = computed(() => {
  return cartItems.value.reduce((sum, item) => sum + (item.price || 0), 0)
})

// Remove item from cart
const removeFromCart = (courseId: number) => {
  const userId = getUserId()
  if (!userId) return

  CartStorage.removeItem(userId, courseId)
  loadCartItems()

  notification.success({
    message: 'Đã xóa khóa học khỏi giỏ hàng'
  })
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
    router.push('/profile')
  } else if (key === 'courses') {
    router.push('/my-courses/' + (authStore.user?.id || ''))
  } else if (key === 'settings') {
    router.push('/settings')
  }
}

function isActive(path: string) {
  return route.path === path
}
</script>
