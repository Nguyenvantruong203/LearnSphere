<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <a-result
      status="403"
      title="403"
      sub-title="Xin lỗi, bạn không có quyền truy cập trang này."
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
