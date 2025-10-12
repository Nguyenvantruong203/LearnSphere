<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item>
          <span class="text-gray-400">Pages</span>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span class="text-gray-700 font-bold">Coupons</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <CouponsTable />
  </LayoutAdmin>
</template>

<script setup lang="ts">
import LayoutAdmin from '../layout/LayoutAdmin.vue'
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue'
import CouponsTable from '@/components/admin/coupon/CouponsTable.vue'
</script>
