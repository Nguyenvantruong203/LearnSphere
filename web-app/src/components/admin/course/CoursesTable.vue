<template>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="mb-6 flex justify-between items-center">
            <a-input-search 
                placeholder="Tìm kiếm theo tiêu đề..." 
                class="w-72" 
                v-model:value="searchQuery"
                @search="fetchCourses"
            />
            <a-button type="primary" @click="showAddModal">
                <PlusOutlined /> Thêm khóa học
            </a-button>
        </div>

        <div class="table-container" style="height: 700px; overflow-y: auto;">
            <a-table 
                :columns="columns" 
                :data-source="courses" 
                :pagination="false" 
                :loading="loading" 
                class="courses-table"
                row-key="id"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'thumbnail'">
                        <a-avatar :src="record.thumbnail_url" :size="40" shape="square" />
                    </template>
                    <template v-else-if="column.key === 'title'">
                        <div class="font-bold">{{ record.title }}</div>
                        <div class="text-xs text-gray-500">{{ record.slug }}</div>
                    </template>
                    <template v-else-if="column.key === 'status'">
                        <a-tag :color="getStatusColor(record.status)">{{ record.status }}</a-tag>
                    </template>
                    <template v-else-if="column.key === 'price'">
                        <span>{{ formatPrice(record.price, record.currency) }}</span>
                    </template>
                     <template v-else-if="column.key === 'creator'">
                        <span>{{ record.creator?.name || 'N/A' }}</span>
                    </template>
                    <template v-else-if="column.key === 'action'">
                        <a-button type="text" size="small" class="text-gray-500 font-bold" @click="showEditModal(record as Course)">
                            <EditOutlined />
                        </a-button>
                        <a-popconfirm
                            title="Bạn có chắc muốn xóa khóa học này?"
                            ok-text="Xóa"
                            cancel-text="Hủy"
                            @confirm="handleDelete(record.slug)"
                        >
                            <a-button type="text" size="small" danger>
                                <DeleteOutlined />
                            </a-button>
                        </a-popconfirm>
                    </template>
                </template>
            </a-table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            <a-pagination 
                v-model:current="pagination.current" 
                v-model:pageSize="pagination.pageSize"
                :total="pagination.total" 
                :show-size-changer="true"
                :page-size-options="['10', '20', '50']" 
                @change="handlePageChange" 
            />
        </div>

        <!-- Modals -->
        <AddCourseModal v-model:visible="isAddModalVisible" @finish="handleModalFinish" />
        <EditCourseModal v-model:visible="isEditModalVisible" :course="selectedCourse" @finish="handleModalFinish" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue';
import { courseApi, type GetCoursesParams } from '@/api/courseApi';
import type { Course } from '@/types/Course';
import { notification } from 'ant-design-vue';
import { EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';
import AddCourseModal from './actions/AddCourseModal.vue';
import EditCourseModal from './actions/EditCourseModal.vue';

const courses = ref<Course[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);
const searchQuery = ref('');

const isAddModalVisible = ref(false);
const isEditModalVisible = ref(false);
const selectedCourse = ref<Course | null>(null);

const pagination = reactive({
    current: 1,
    pageSize: 10,
    total: 0,
});

const columns = [
    { title: 'Ảnh bìa', key: 'thumbnail', width: '10%' },
    { title: 'Tiêu đề', dataIndex: 'title', key: 'title', width: '30%' },
    { title: 'Trạng thái', dataIndex: 'status', key: 'status', width: '15%' },
    { title: 'Giá', dataIndex: 'price', key: 'price', width: '15%' },
    { title: 'Người tạo', dataIndex: ['creator', 'name'], key: 'creator', width: '15%' },
    { title: 'Hành động', key: 'action', width: '15%' },
];

const fetchCourses = async () => {
    loading.value = true;
    error.value = null;
    try {
        const params: GetCoursesParams = {
            page: pagination.current,
            limit: pagination.pageSize,
            search: searchQuery.value || undefined,
        };
        const response = await courseApi.getCourses(params);
        courses.value = response.data;
        pagination.total = response.total;
    } catch (e: any) {
        error.value = e.message || 'Failed to fetch courses.';
        notification.error({ message: error.value });
    } finally {
        loading.value = false;
    }
};

const handlePageChange = (page: number, pageSize: number) => {
    pagination.current = page;
    pagination.pageSize = pageSize;
    fetchCourses();
};

const showAddModal = () => {
    isAddModalVisible.value = true;
};

const showEditModal = (course: Course) => {
    selectedCourse.value = course;
    isEditModalVisible.value = true;
};

const handleModalFinish = () => {
    fetchCourses(); // Refresh the table after adding/editing
};

const handleDelete = async (slug: string) => {
    try {
        await courseApi.deleteCourse(slug);
        notification.success({ message: 'Xóa khóa học thành công!' });
        fetchCourses(); // Refresh table
    } catch (e: any) {
        notification.error({ message: e.message || 'Xóa khóa học thất bại.' });
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'published': return 'green';
        case 'draft': return 'blue';
        case 'archived': return 'red';
        default: return 'gray';
    }
};

const formatPrice = (price: number, currency: string) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: currency }).format(price);
};

onMounted(fetchCourses);

let debounceTimer: number;
watch(searchQuery, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        pagination.current = 1;
        fetchCourses();
    }, 500);
});
</script>
