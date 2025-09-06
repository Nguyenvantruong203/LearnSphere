<template>
  <header class="p-4">
    <div class="flex items-center justify-between">
      <div>
        <a-breadcrumb class="mb-2">
          <a-breadcrumb-item>
            <span class="text-gray-400">Pages</span>
          </a-breadcrumb-item>
          <a-breadcrumb-item>
            <span class="text-gray-700 font-bold">Tables</span>
          </a-breadcrumb-item>
        </a-breadcrumb>
      </div>
      <div class="flex items-center space-x-4">

        <div class="flex items-center space-x-3">
          <a-button type="text" size="small">
            Sign In
          </a-button>
          <a-button type="text" size="small">
            <span class="flex items-center justify-center">
              <BellOutlined />
            </span>
          </a-button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import {
  BellOutlined
} from '@ant-design/icons-vue'
</script>
