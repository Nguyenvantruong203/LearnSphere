<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <a-result
      status="404"
      title="404"
      sub-title="Xin lỗi, trang bạn truy cập không tồn tại."
    >
      <template #extra>
        <router-link to="/">
          <a-button type="primary">Về trang chủ</a-button>
        </router-link>
      </template>
    </a-result>
  </div>
</template>

<script setup lang="ts">
// No script logic needed for this simple page
</script>
