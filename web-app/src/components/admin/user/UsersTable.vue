<template>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="mb-6 flex justify-between items-center">
            <a-input-search placeholder="Tìm kiếm theo tên, email..." class="w-72" v-model:value="searchQuery"
                @search="fetchUsers"></a-input-search>
        </div>

        <div class="table-container" style="height: 700px; overflow-y: auto;">
            <a-table :columns="columns" :data-source="users" :pagination="false" :loading="loading" class="users-table"
                row-key="id">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'name'">
                        <UserCell :user="(record as User)" />
                    </template>
                    <template v-else-if="column.key === 'birth_date'">
                        <FormatDate :date="(record as User).birth_date" />
                    </template>
                    <template v-else-if="column.key === 'gender'">
                        <span>{{ record.gender == 'male' ? 'Nam' : 'Nữ' }}</span>
                    </template>
                    <template v-else-if="column.key === 'status'">
                        <template v-if="record.status === 'pending'">
                            <a-tag color="green">Đang chờ</a-tag>
                        </template>
                        <template v-else-if="record.status === 'approved'">
                            <a-tag color="red">Đã duyệt</a-tag>
                        </template>
                        <template v-else>
                            <a-tag color="gray">Từ chối</a-tag>
                        </template>
                    </template>
                    <template v-else-if="column.key === 'role'">
                        <template v-if="record.role === 'admin'">
                            <a-tag color="blue">Admin</a-tag>
                        </template>
                        <template v-else-if="record.role === 'instructor'">
                            <a-tag color="purple">Giảng viên</a-tag>
                        </template>
                        <template v-else>
                            <a-tag color="green">Người dùng</a-tag>
                        </template>
                    </template>
                    <template v-else-if="column.key === 'action'">
                        <a-button type="text" size="small" class="text-gray-500 font-bold"
                            @click="showEditModal(record as User)">
                            <EditOutlined />
                        </a-button>
                    </template>
                </template>
            </a-table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            <a-pagination v-model:current="pagination.current" v-model:pageSize="pagination.pageSize"
                :total="pagination.total" :show-size-changer="pagination.showSizeChanger"
                :page-size-options="pagination.pageSizeOptions" @change="handlePageChange" />
        </div>

        <!-- Edit User Modal -->
        <EditUserModal v-model:visible="isEditModalVisible" :user="selectedUser" @finish="handleEditFinish" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue'
import { userApi } from '@/api/admin/userApi'
import type { User, GetUsersParams } from '@/types/User'
import UserCell from './UserCell.vue'
import FormatDate from './FormatDate.vue'
import EditUserModal from './actions/EditUserModal.vue'
import { notification } from 'ant-design-vue'
import { EditOutlined } from '@ant-design/icons-vue'

const users = ref<User[]>([])
const isEditModalVisible = ref(false);
const selectedUser = ref<User | null>(null);
const loading = ref(false)
const error = ref<string | null>(null)
const searchQuery = ref('')

const pagination = reactive({
    current: 1,
    pageSize: 10,
    total: 0,
    showSizeChanger: true,
    pageSizeOptions: ['10', '20', '50'],
});

const columns = [
    {
        title: 'Full name',
        dataIndex: 'name',
        key: 'name',
        width: '30%',
    },
    {
        title: 'Date of Birth',
        dataIndex: 'birth_date',
        key: 'birth_date',
        width: '15%',
    },
    {
        title: 'Gender',
        dataIndex: 'gender',
        key: 'gender',
        width: '25%',
    },
    {
        title: 'Role',
        dataIndex: 'role',
        key: 'role',
        width: '15%',
    },
    {
        title: 'Status',
        dataIndex: 'status',
        key: 'status',
        width: '15%',
    },
    {
        title: 'Action',
        key: 'action',
        width: '15%',
    },
]

const fetchUsers = async () => {
  loading.value = true
  error.value = null
  try {
    const params: GetUsersParams = {
      page: pagination.current,
      per_page: pagination.pageSize,
      search: searchQuery.value || undefined,
    };
    const response = await userApi.getUsers(params)

    users.value = response.data
  } catch (e: any) {
    error.value = e.message || 'Failed to fetch users.'
    notification.error({ message: error.value })
  } finally {
    loading.value = false
  }
}


const handlePageChange = (page: number, pageSize: number) => {
    pagination.current = page;
    pagination.pageSize = pageSize;
    fetchUsers();
};

const showEditModal = (user: User) => {
    selectedUser.value = user;
    isEditModalVisible.value = true;
};

const handleEditFinish = () => {
    fetchUsers(); // Refresh the table after editing
};

onMounted(fetchUsers)

let debounceTimer: number;
watch(searchQuery, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        pagination.current = 1;
        fetchUsers();
    }, 500);
});

</script>
