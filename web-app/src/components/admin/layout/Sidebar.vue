<template>
  <aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-gray-50 to-white z-10 px-6">
    <!-- Logo -->
    <div class="flex items-center justify-center pt-6 pb-8">
      <router-link to="/admin/courses" class="transform transition-transform duration-300 hover:scale-105">
        <img 
          src="@/assets/images/logo.png" 
          alt="LearnSphere Logo"
          class="w-36 h-22 object-contain drop-shadow-md"
        />
      </router-link>
    </div>
    
    <!-- Navigation -->
    <nav class="space-y-1">
      <!-- Main -->
      <router-link 
        v-for="item in filteredMainNavigation" 
        :key="item.label" 
        :to="item.path"
        class="block"
      >
        <SidebarItem 
          :icon="item.icon"
          :label="item.label"
          :active="route.path === item.path"
        />
      </router-link>

      <!-- Divider -->
      <div class="pt-6 pb-3">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest px-4">
          Account
        </p>
      </div>

      <!-- Account -->
      <router-link 
        v-for="item in filteredAccountPages" 
        :key="item.label" 
        :to="item.path"
        class="block"
      >
        <SidebarItem 
          :icon="item.icon"
          :label="item.label"
          :active="route.path === item.path"
        />
      </router-link>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'
import { 
  HomeOutlined, 
  BarChartOutlined, 
  CreditCardOutlined, 
  ToolOutlined,
  UserOutlined,
  FileTextOutlined,
  RocketOutlined,
  PartitionOutlined,
  BookOutlined
} from '@ant-design/icons-vue'
import SidebarItem from './SidebarItem.vue'

const route = useRoute()
const authStore = useAdminAuthStore()
const role = computed(() => authStore.user?.role || '')

// === Navigation cấu hình ===
const mainNavigation = [
  { icon: HomeOutlined, label: 'Dashboard', path: '/admin', roles: ['admin', 'instructor'] },
  { icon: BarChartOutlined, label: 'Users Management', path: '/admin/users', roles: ['admin'] },
  { icon: BookOutlined, label: 'Courses', path: '/admin/courses', roles: ['instructor'] },
  { icon: FileTextOutlined, label: 'Coupons', path: '/admin/coupons', roles: ['admin'] },
]

const accountPages = [
  { icon: UserOutlined, label: 'Profile', path: '/admin/profile', roles: ['admin', 'instructor'] },
]

// === Lọc theo role người dùng ===
const filteredMainNavigation = computed(() =>
  mainNavigation.filter(item => item.roles.includes(role.value))
)

const filteredAccountPages = computed(() =>
  accountPages.filter(item => item.roles.includes(role.value))
)
</script>
