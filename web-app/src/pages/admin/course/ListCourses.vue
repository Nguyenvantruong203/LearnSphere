<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item>
          <span class="text-gray-400">Pages</span>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span class="text-gray-700 font-bold">List Course</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <CoursesTable />
  </LayoutAdmin>
</template>

<script setup lang="ts">
import LayoutAdmin from '../layout/LayoutAdmin.vue';
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue';
import CoursesTable from '@/components/admin/course/CoursesTable.vue';
</script>
