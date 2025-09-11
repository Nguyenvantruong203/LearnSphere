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

        <TopicsTable :topics="topics" :loading="loading" :pagination="pagination" @add="showAddModal = true"
            @edit="handleEdit" @delete="handleDelete" @page-change="handlePageChange" />

        <AddTopicModal v-model:visible="showAddModal" @finish="fetchData" />

        <EditTopicModal v-model:visible="showEditModal" :topic="selectedTopic" @finish="fetchData" />
    </LayoutAdmin>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { topicApi } from '@/api/topicApi';
import type { Topic, PaginatedTopics } from '@/types/Topic';
import { notification } from 'ant-design-vue';
import LayoutAdmin from '../layout/LayoutAdmin.vue';
import HeaderAdmin from '@/components/admin/layout/HeaderAdmin.vue';
import TopicsTable from '@/components/admin/topic/TopicsTable.vue';
import AddTopicModal from '@/components/admin/topic/actions/AddTopicModal.vue';
import EditTopicModal from '@/components/admin/topic/actions/EditTopicModal.vue';

const topics = ref<Topic[]>([]);
const loading = ref(false);
const pagination = ref<Omit<PaginatedTopics, 'data'>>();
const currentPage = ref(1);

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedTopic = ref<Topic | null>(null);

const fetchData = async () => {
    loading.value = true;
    try {
        const res = await topicApi.getTopics({ page: currentPage.value });
        topics.value = res.data;
        const { data, ...rest } = res;
        pagination.value = rest;
    } catch (error: any) {
        notification.error({ message: error.message || 'Tải danh sách chủ đề thất bại.' });
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

const handlePageChange = (page: number) => {
    currentPage.value = page;
    fetchData();
};

const handleEdit = (topic: Topic) => {
    selectedTopic.value = topic;
    showEditModal.value = true;
};

const handleDelete = async (id: number) => {
    try {
        await topicApi.deleteTopic(id);
        notification.success({ message: 'Xóa chủ đề thành công!' });
        fetchData();
    } catch (error: any) {
        notification.error({ message: error.message || 'Xóa chủ đề thất bại.' });
    }
};
</script>
