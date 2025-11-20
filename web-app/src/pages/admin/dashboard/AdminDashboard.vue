<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item><span class="text-gray-400">Pages</span></a-breadcrumb-item>
        <a-breadcrumb-item><span class="text-gray-700 font-bold">Dashboard</span></a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <AdminDashboardContent />
  </LayoutAdmin>
</template>

<script setup lang="ts">
import LayoutAdmin from '@/pages/admin/layout/LayoutAdmin.vue';
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue';
import AdminDashboardContent from '@/components/admin/dashboard/AdminDashboardContent.vue';
</script>
