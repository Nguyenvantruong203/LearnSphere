<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item>
          <span class="text-gray-400">Quản lý</span>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span class="text-gray-700 font-bold">Duyệt khóa học</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <CoursesTable />
  </LayoutAdmin>
</template>

<script setup lang="ts">
import LayoutAdmin from '../layout/LayoutAdmin.vue'
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue'
import CoursesTable from '@/components/admin/course/CoursesTable.vue'
</script>
