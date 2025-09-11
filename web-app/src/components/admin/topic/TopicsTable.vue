<template>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Danh sách Chủ đề</h2>
            <a-button type="primary" class="flex justify-center items-center" @click="emit('add')"><PlusOutlined /><span>Thêm Chủ đề</span></a-button>
        </div>
        <a-table
            :columns="columns"
            :data-source="topics"
            :loading="loading"
            :pagination="false"
            row-key="id"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'course'">
                    <a-tag color="blue">{{ record.course?.title }}</a-tag>
                </template>
                <template v-if="column.key === 'order'">
                    <a-tag>{{ record.order }}</a-tag>
                </template>
                <template v-if="column.key === 'actions'">
                    <a-space>
                        <a-button  type="text" size="small"  @click="emit('edit', record)"><EditOutlined /></a-button>
                        <a-popconfirm
                            title="Bạn có chắc muốn xóa chủ đề này?"
                            ok-text="Xóa"
                            cancel-text="Hủy"
                            @confirm="emit('delete', record.id)"
                        >
                            <a-button danger type="text" size="small"><DeleteOutlined /></a-button>
                        </a-popconfirm>
                    </a-space>
                </template>
            </template>
        </a-table>
        <div v-if="pagination" class="flex justify-end mt-4">
            <a-pagination
                :current="pagination.current_page"
                :total="pagination.total"
                :page-size="pagination.per_page"
                @change="(page) => emit('page-change', page)"
                show-size-changer
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Topic, PaginatedTopics } from '@/types/Topic';
import { EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons-vue';

interface Props {
    topics: Topic[];
    loading: boolean;
    pagination?: Omit<PaginatedTopics, 'data'>;
}

defineProps<Props>();
const emit = defineEmits(['add', 'edit', 'delete', 'page-change']);

import type { TableColumnsType } from 'ant-design-vue';

const columns: TableColumnsType = [
    { title: 'ID', dataIndex: 'id', key: 'id', width: 80 },
    { title: 'Tiêu đề', dataIndex: 'title', key: 'title' },
    { title: 'Khóa học', key: 'course', width: 200 },
    { title: 'Thứ tự', dataIndex: 'order', key: 'order', width: 100 },
    { title: 'Hành động', key: 'actions', width: 150, align: 'center' },
];
</script>
