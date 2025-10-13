<template>
  <div class="min-h-screen w-screen flex items-center justify-center">
    <div class="w-full h-full flex items-center justify-center">
      <a-result
        status="404"
        title="404"
        sub-title="Trang bạn tìm kiếm không tồn tại."
        class="bg-white p-8 w-[90%] max-w-2xl"
      >
        <template #icon>
          <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center">
              <SearchOutlined class="text-4xl text-blue-500" />
            </div>
          </div>
        </template>
        
        <template #title>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Không tìm thấy trang
          </h1>
        </template>

        <template #extra>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a-button 
              type="primary" 
              size="large"
              @click="goBack"
              class="flex items-center gap-2"
            >
              <LeftOutlined />
              Quay lại
            </a-button>
          </div>
        </template>
      </a-result>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { SearchOutlined, LeftOutlined } from '@ant-design/icons-vue'

const router = useRouter()

const goBack = () => {
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    router.push('/')
  }
}
</script>
