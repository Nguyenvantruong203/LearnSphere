<template>
  <aside class="fixed left-0 top-0 h-screen w-64 z-10 px-8">
    <!-- Logo -->
    <div class="flex items-center justify-center pt-5 pb-3">
      <router-link to="/">
        <img 
          src="@/assets/images/logo.png" alt="LearnSphere Logo"
          class="w-32 h-20 object-contain"
        />
      </router-link>
    </div>
    
    <!-- Navigation -->
    <nav class="border-t border-gray-200 pt-5">
      <router-link v-for="item in mainNavigation" :key="item.label" :to="item.path">
        <SidebarItem 
          :icon="item.icon"
          :label="item.label"
          :active="route.path === item.path"
        />
      </router-link>
      
      <div class="mt-8 mb-4">
        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider px-4">
          Account Pages
        </p>
      </div>
      
      <router-link v-for="item in accountPages" :key="item.label" :to="item.path">
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
];  

const accountPages = [
  { icon: UserOutlined, label: 'Profile', path: '/admin/profile' },
];
</script>
