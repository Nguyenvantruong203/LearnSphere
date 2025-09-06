<template>
  <div class="min-h-screen bg-admin">
    <div class="flex">
      <!-- Sidebar -->
      <Sidebar />
      
      <!-- Main Content -->
      <div class="flex-1 ml-64">
        <!-- Header -->
        <Header />
        
        <!-- Content -->
        <main class="py-4">
          <!-- Authors Table -->
          <slot />

          <!-- Projects Table -->
          <!-- <ProjectsTable /> -->
        </main>
        
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Sidebar from '@/components/admin/layout/Sidebar.vue'
import Header from '@/components/admin/layout/HeaderAdmin.vue'
</script>
