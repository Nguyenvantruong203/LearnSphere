<template>
  <div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- 🔍 Search & Filter -->
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
      <div class="flex items-center gap-4">
        <a-select v-model:value="selectedRole" placeholder="Filter by role" style="width: 180px"
          @change="handleFilterChange">
          <a-select-option value="">All Roles</a-select-option>
          <a-select-option value="student">Student</a-select-option>
          <a-select-option value="instructor">Instructor</a-select-option>
        </a-select>

        <a-input-search placeholder="Search by name or email..." class="w-72" v-model:value="searchQuery"
          @search="fetchUsers" />
      </div>
    </div>

    <!-- 📋 Table -->
    <div class="table-container h-[calc(100vh-245px)]">
      <a-table :columns="columns" :data-source="users" :pagination="false" :loading="loading" row-key="id"
        class="users-table cursor-pointer" :customRow="handleRowClick">

        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'name'">
            <UserCell :user="record as User" />
          </template>

          <template v-else-if="column.key === 'birth_date'">
            <FormatDate :date="record.birth_date" />
          </template>

          <template v-else-if="column.key === 'gender'">
            <span>{{ record.gender ? (record.gender === 'male' ? 'Male' : 'Female') : '' }}</span>
          </template>

          <template v-else-if="column.key === 'status'">
            <a-tag :color="getStatusColor(record.status)">
              {{ record.status.charAt(0).toUpperCase() + record.status.slice(1) }}
            </a-tag>
          </template>

          <template v-else-if="column.key === 'role'">
            <a-tag :color="getRoleColor(record.role)">
              {{ record.role.charAt(0).toUpperCase() + record.role.slice(1) }}
            </a-tag>
          </template>
        </template>
      </a-table>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-6 flex justify-center">
      <a-pagination v-model:current="pagination.current" v-model:pageSize="pagination.pageSize"
        :total="pagination.total" :show-total="(total) => `Total ${total} users`"
        :show-size-changer="pagination.showSizeChanger" :page-size-options="pagination.pageSizeOptions"
        @change="handlePageChange" />
    </div>

    <!-- 🔎 Drawer thông tin người dùng -->
    <UserDrawer v-model:visible="isDrawerVisible" :user="selectedUser" @refresh="fetchUsers" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue'
import { userApi } from '@/api/admin/userApi'
import type { User, GetUsersParams, PaginationUser } from '@/types/User'
import UserCell from './UserCell.vue'
import FormatDate from '@/components/common/FormatDate.vue'
import UserDrawer from './actions/UserDrawer.vue'
import { notification } from 'ant-design-vue'

// ==================== STATE ====================
const users = ref<User[]>([])
const loading = ref(false)
const searchQuery = ref('')
const selectedRole = ref<'student' | 'instructor' | ''>('instructor')
const isDrawerVisible = ref(false)
const selectedUser = ref<User | null>(null)
const error = ref<string | null>(null)

// ==================== PAGINATION ====================
const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showSizeChanger: true,
  pageSizeOptions: ['10', '20', '50'],
})

// ==================== TABLE COLUMNS ====================
const columns = [
  { title: 'User Information', dataIndex: 'name', key: 'name', width: '30%' },
  { title: 'Date of Birth', dataIndex: 'birth_date', key: 'birth_date', width: '15%' },
  { title: 'Gender', dataIndex: 'gender', key: 'gender', width: '10%' },
  { title: 'Role', dataIndex: 'role', key: 'role', width: '15%' },
  { title: 'Status', dataIndex: 'status', key: 'status', width: '15%' },
]

// ==================== HELPERS ====================
const getStatusColor = (status: string) => {
  switch (status) {
    case 'approved': return 'green'
    case 'pending': return 'orange'
    case 'rejected': return 'red'
    default: return 'gray'
  }
}

const getRoleColor = (role: string) => {
  switch (role) {
    case 'admin': return 'blue'
    case 'instructor': return 'purple'
    case 'student': return 'cyan'
    default: return 'gray'
  }
}

// ==================== FETCH USERS ====================
const fetchUsers = async () => {
  loading.value = true
  error.value = null
  try {
    const params: GetUsersParams = {
      page: pagination.current,
      per_page: pagination.pageSize,
      search: searchQuery.value || undefined,
      role: selectedRole.value || undefined,
    }

    const response: PaginationUser<User> = await userApi.getUsers(params)
    users.value = response.data
    pagination.total = response.total
  } catch (e: any) {
    error.value = e.message || 'Failed to fetch users.'
    notification.error({ message: error.value })
  } finally {
    loading.value = false
  }
}

// ==================== EVENTS ====================
const handlePageChange = (page: number, pageSize: number) => {
  pagination.current = page
  pagination.pageSize = pageSize
  fetchUsers()
}

const handleFilterChange = () => {
  pagination.current = 1
  fetchUsers()
}

const handleRowClick = (record: User) => {
  return {
    onClick: () => {
      selectedUser.value = record
      isDrawerVisible.value = true
    },
  }
}


onMounted(fetchUsers)
</script>

<style scoped>
.table-container {
  overflow-y: auto;
}

.users-table tr:hover {
  background-color: #f5f8ff !important;
  cursor: pointer;
}
</style>
