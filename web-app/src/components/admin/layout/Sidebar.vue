<template>
  <aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-gray-50 to-white z-10 px-6">
    <!-- Logo -->
    <div class="flex items-center justify-center pt-6 pb-8">
      <router-link to="/" class="transform transition-transform duration-300 hover:scale-105">
        <img 
          src="@/assets/images/logo.png" alt="LearnSphere Logo"
          class="w-36 h-22 object-contain drop-shadow-md"
        />
      </router-link>
    </div>
    
    <!-- Navigation -->
    <nav class="space-y-1">
      <router-link 
        v-for="item in mainNavigation" 
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
      
      <div class="pt-6 pb-3">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest px-4">
          Account
        </p>
      </div>
      
      <router-link 
        v-for="item in accountPages" 
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
import { useRoute } from 'vue-router'
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

const route = useRoute();

const mainNavigation = [
  { icon: HomeOutlined, label: 'Dashboard', path: '/admin' },
  { icon: BarChartOutlined, label: 'Users management', path: '/admin/users' },
  { icon: BookOutlined, label: 'Courses', path: '/admin/courses' },
  { icon: FileTextOutlined, label: 'Coupons', path: '/admin/coupons' },
];  

const accountPages = [
  { icon: UserOutlined, label: 'Profile', path: '/admin/profile' },
];
</script>