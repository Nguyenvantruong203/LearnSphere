<template>
  <div class="flex items-center">
    <a-avatar 
      :src="user.avatar_url" 
      :size="40" 
      class="mr-3"
    >
      <!-- Fallback icon -->
      <template #icon><UserOutlined /></template>
    </a-avatar>
    <div>
      <div class="font-bold text-sm text-gray-700">{{ user.name }}</div>
      <div class="text-sm text-gray-500">{{ user.email }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { User } from '@/types/user'
import { UserOutlined } from '@ant-design/icons-vue'

interface Props {
  user: User
}

defineProps<Props>()
</script>
