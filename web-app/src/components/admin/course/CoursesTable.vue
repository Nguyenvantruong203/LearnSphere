<template>
  <div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center gap-4">
        <h2 class="text-lg font-semibold text-gray-800">Course Approval List</h2>
        <a-badge :count="coursesCount" style="background: #1677ff">
          <span class="text-xs text-gray-500 px-2 py-1 bg-gray-100">
            Total
          </span>
        </a-badge>
      </div>

      <div class="flex gap-3">
        <a-select v-model:value="filters.status" placeholder="Filter status" class="w-36" allowClear
          @change="loadCourses">
          <a-select-option value="all">All statuses</a-select-option>
          <a-select-option value="pending">Pending</a-select-option>
          <a-select-option value="approved">Approved</a-select-option>
          <a-select-option value="rejected">Rejected</a-select-option>
        </a-select>

        <a-input v-model:value="searchText" placeholder="Search course name..." class="w-72" allowClear
          @pressEnter="loadCourses">
          <template #suffix>
            <SearchOutlined @click="loadCourses" />
          </template>
        </a-input>
      </div>
    </div>

    <!-- Table -->
    <a-table :dataSource="courses" :columns="columns" :pagination="pagination" :loading="loading" :rowKey="'id'"
      size="small" @change="handleTableChange" :customRow="onRowClick">
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'thumbnail'">
          <div class="w-16 h-12 bg-gray-100 rounded overflow-hidden">
            <img v-if="record.thumbnail_url" :src="record.thumbnail_url" :alt="record.title"
              class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400">
              No image
            </div>
          </div>
        </template>

        <template v-else-if="column.dataIndex === 'course'">
          <div>
            <h3 class="font-medium text-gray-800 truncate max-w-xs" :title="record.title">
              {{ record.title }}
            </h3>
            <p class="text-sm text-gray-500">
              Instructor: {{ record.instructor?.name || 'N/A' }}
            </p>
          </div>
        </template>

        <template v-else-if="column.dataIndex === 'status'">
          <a-tag :color="getStatusColor(record.status)">
            {{ getStatusLabel(record.status) }}
          </a-tag>
        </template>

        <template v-else-if="column.dataIndex === 'level'">
          <a-tag :color="getLevelColor(record.level)">
            {{ getLevelLabel(record.level) }}
          </a-tag>
        </template>

        <template v-else-if="column.dataIndex === 'price'">
          <span v-if="record.price > 0" class="font-medium text-green-600">
            {{ formatPrice(record.price) }}
          </span>
          <a-tag v-else color="cyan">Free</a-tag>
        </template>

        <template v-else-if="column.dataIndex === 'created_at'">
          <div class="text-sm text-gray-600">
            {{ formatDate(record.created_at) }}
          </div>
        </template>
      </template>
    </a-table>

    <CourseDrawer v-model:visible="drawerVisible" :course="selectedCourse" @refresh="loadCourses" />
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { approveCourseApi } from '@/api/admin/approveCourseApi'
import { notification } from 'ant-design-vue'
import { SearchOutlined } from '@ant-design/icons-vue'
import type { Course } from '@/types/Course'
import CourseDrawer from './CourseDrawer.vue'
import dayjs from 'dayjs'

const loading = ref(false)
const courses = ref<Course[]>([])
const coursesCount = ref(0)
const searchText = ref('')
const drawerVisible = ref(false)
const selectedCourse = ref<Course | null>(null)

const filters = reactive({
  status: 'all' as string | undefined
})

const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total: number, range: number[]) =>
    `${range[0]}-${range[1]} of ${total} courses`
})

const columns = [
  { title: 'Thumbnail', dataIndex: 'thumbnail', key: 'thumbnail', width: 100, align: 'center' },
  { title: 'Course', dataIndex: 'course', key: 'course', ellipsis: true },
  { title: 'Status', dataIndex: 'status', key: 'status', width: 120, align: 'center' },
  { title: 'Level', dataIndex: 'level', key: 'level', width: 100, align: 'center' },
  { title: 'Price', dataIndex: 'price', key: 'price', width: 150, align: 'center' },
  { title: 'Created At', dataIndex: 'created_at', key: 'created_at', width: 120, align: 'center' },
]

const loadCourses = async () => {
  loading.value = true
  try {
    const params = {
      page: pagination.current,
      limit: pagination.pageSize,
      search: searchText.value || undefined,
      status: filters.status === 'all' ? undefined : filters.status
    }

    const response = await approveCourseApi.getCourses(params)

    courses.value = response.data
    pagination.total = response.total
    coursesCount.value = response.total
  } catch (error: any) {
    notification.error({
      message: 'Error',
      description: error.message || 'Failed to load courses'
    })
  } finally {
    loading.value = false
  }
}

const handleTableChange = (pag: any) => {
  pagination.current = pag.current
  pagination.pageSize = pag.pageSize
  loadCourses()
}

const getStatusColor = (status: string) => {
  switch (status) {
    case 'approved': return 'green'
    case 'pending': return 'orange'
    case 'rejected': return 'red'
    case 'draft': return 'gray'
    case 'archived': return 'purple'
    default: return 'default'
  }
}

const getStatusLabel = (status: string) => {
  switch (status) {
    case 'approved': return 'Approved'
    case 'pending': return 'Pending'
    case 'rejected': return 'Rejected'
    case 'draft': return 'Draft'
    case 'archived': return 'Archived'
    default: return status
  }
}

const getLevelColor = (level: string) => {
  switch (level) {
    case 'beginner': return 'green'
    case 'intermediate': return 'orange'
    case 'advanced': return 'red'
    default: return 'default'
  }
}

const getLevelLabel = (level: string) => {
  switch (level) {
    case 'beginner': return 'Beginner'
    case 'intermediate': return 'Intermediate'
    case 'advanced': return 'Advanced'
    default: return level
  }
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'USD'
  }).format(price)
}

const formatDate = (dateString: string) => {
  return dayjs(dateString).format('DD/MM/YYYY')
}

const onRowClick = (record: Course) => {
  return {
    onClick: () => {
      selectedCourse.value = record
      drawerVisible.value = true
    }
  }
}

onMounted(() => loadCourses())
</script>

<style scoped>
:deep(.ant-table-tbody > tr > td) {
  padding: 12px 8px;
}

:deep(.ant-table-thead > tr > th) {
  background: #f8fafc;
  font-weight: 600;
  border-bottom: 2px solid #e5e7eb;
}

:deep(.ant-table-row:hover > td) {
  background-color: #f0f9ff;
}
</style>
