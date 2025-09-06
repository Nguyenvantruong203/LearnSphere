<template>
  <aside class="fixed left-0 top-0 h-screen w-64 z-10 px-8">
    <!-- Logo -->
    <div class="flex items-center justify-center pt-5 pb-3">
      <img 
        src="@/assets/images/logo.png" alt="LearnSphere Logo"
        class="w-32 h-20 object-contain"
      />
    </div>
    
    <!-- Navigation -->
    <nav class="border-t border-gray-200 pt-5">
      <SidebarItem 
        :icon="HomeOutlined"
        label="Dashboard"
        :active="true"
      />
      <SidebarItem 
        :icon="BarChartOutlined"
        label="Tables"
      />
      <SidebarItem 
        :icon="CreditCardOutlined"
        label="Billing"
      />
      <SidebarItem 
        :icon="ToolOutlined"
        label="RTL"
      />
      
      <div class="mt-8 mb-4">
        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider px-4">
          Account Pages
        </p>
      </div>
      
      <SidebarItem 
        :icon="UserOutlined"
        label="Profile"
      />
      <SidebarItem 
        :icon="FileTextOutlined"
        label="Sign In"
      />
      <SidebarItem 
        :icon="RocketOutlined"
        label="Sign Up"
      />
    </nav>
  </aside>
</template>

<script setup lang="ts">
import { 
  HomeOutlined, 
  BarChartOutlined, 
  CreditCardOutlined, 
  ToolOutlined,
  UserOutlined,
  FileTextOutlined,
  RocketOutlined
} from '@ant-design/icons-vue'
import SidebarItem from './SidebarItem.vue'
</script>
