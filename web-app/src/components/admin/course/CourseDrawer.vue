<template>
  <a-drawer :open="visible" :width="1000" title="Course Details" @close="handleClose" class="course-drawer">
    <div v-if="course">
      <!-- Pending -->
      <a-alert
        v-if="course.status === 'pending'"
        message="This course is pending approval"
        description="Review the course information and decide whether to approve or reject."
        type="warning"
        show-icon
        class="mb-6"
      />

      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
        <div class="flex items-start gap-6">
          <!-- Thumbnail -->
          <div class="w-32 h-24 rounded-lg bg-gray-200 flex-shrink-0 overflow-hidden">
            <img
              v-if="course.thumbnail_url"
              :src="course.thumbnail_url"
              :alt="course.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
              <span class="text-sm">No image</span>
            </div>
          </div>

          <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ course.title }}</h2>

            <div class="flex flex-wrap gap-3 mb-4">
              <a-tag :color="getStatusColor(course.status)" class="text-sm px-3 py-1">
                {{ getStatusLabel(course.status) }}
              </a-tag>
              <a-tag :color="getLevelColor(course.level)" class="text-sm px-3 py-1">
                {{ getLevelLabel(course.level) }}
              </a-tag>

              <a-tag v-if="course.price && course.price > 0" color="green" class="text-sm px-3 py-1">
                {{ formatPrice(course.price) }}
              </a-tag>
              <a-tag v-else color="cyan" class="text-sm px-3 py-1">
                Free
              </a-tag>
            </div>

            <div class="text-gray-600 space-y-2">
              <div class="flex items-center gap-2">
                <UserOutlined />
                <span>Instructor: <strong>{{ course.instructor?.name || 'N/A' }}</strong></span>
              </div>

              <div class="flex items-center gap-2">
                <BookOutlined />
                <span>{{ course.total_topics || 0 }} chapters • {{ course.total_lessons || 0 }} lessons</span>
              </div>

              <div class="flex items-center gap-2">
                <GlobalOutlined />
                <span>Language: {{ course.language }}</span>
              </div>

              <div class="flex items-center gap-2">
                <CalendarOutlined />
                <span>Created at: {{ formatDate(course.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Details -->
      <div class="space-y-6">
        <a-card v-if="course.short_description" title="Short Description" size="small">
          <p class="text-gray-700 leading-relaxed">{{ course.short_description }}</p>
        </a-card>

        <a-card v-if="course.description" title="Full Description" size="small">
          <div class="prose max-w-none">
            <div v-html="course.description" class="text-gray-700 leading-relaxed"></div>
          </div>
        </a-card>

        <a-card title="Course Information" size="small">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium text-gray-500">Subject</label>
              <p class="font-medium">{{ course.subject || 'Uncategorized' }}</p>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-500">Level</label>
              <p class="font-medium">{{ getLevelLabel(course.level) }}</p>
            </div>
          </div>
        </a-card>

        <a-card v-if="course.instructor" title="Instructor Information" size="small">
          <div class="flex items-center gap-4">
            <div
              class="w-16 h-16 rounded-full bg-indigo-200 flex items-center justify-center text-xl font-bold text-indigo-600"
            >
              {{ getInitials(course.instructor.name) }}
            </div>

            <div>
              <h3 class="font-medium text-lg">{{ course.instructor.name }}</h3>
              <p class="text-gray-600">{{ course.instructor.email }}</p>

              <div class="flex items-center gap-2 mt-1">
                <a-tag :color="getRoleColor(course.instructor.role)" size="small">
                  {{ getRoleLabel(course.instructor.role) }}
                </a-tag>
              </div>
            </div>
          </div>
        </a-card>
      </div>
    </div>

    <!-- Footer Actions -->
    <template #footer>
      <div class="flex justify-between items-center px-6 py-4 border-t bg-gray-50">
        <a-button @click="handleClose" size="large">Close</a-button>

        <div class="flex gap-3">
          <!-- Pending -->
          <template v-if="course?.status === 'pending'">
            <a-button
              type="primary"
              danger
              size="large"
              @click="openRejectModal"
              :loading="loadingReject"
              class="flex items-center gap-2"
            >
              <CloseCircleOutlined /> Reject
            </a-button>

            <a-button
              type="primary"
              size="large"
              @click="approveCourse"
              :loading="loadingApprove"
              class="bg-green-500 hover:bg-green-600 border-green-500 flex items-center gap-2"
            >
              <CheckCircleOutlined /> Approve
            </a-button>
          </template>

          <!-- Rejected -->
          <template v-else-if="course?.status === 'rejected'">
            <a-button
              type="primary"
              size="large"
              @click="approveCourse"
              :loading="loadingApprove"
              class="bg-green-500 hover:bg-green-600 border-green-500 flex items-center gap-2"
            >
              <CheckCircleOutlined /> Approve Again
            </a-button>
          </template>
        </div>
      </div>
    </template>

    <!-- Reject Modal -->
    <a-modal
      v-model:open="rejectModalVisible"
      title="Reject Course"
      @ok="rejectCourse"
      :confirmLoading="loadingReject"
      okText="Confirm rejection"
      cancelText="Cancel"
      :okButtonProps="{ danger: true }"
      width="600px"
    >
      <a-alert
        message="Notice"
        description="The instructor will receive a notification including the rejection reason."
        type="info"
        show-icon
        class="mb-4"
      />

      <a-form-item label="Rejection reason" required>
        <a-textarea
          v-model:value="rejectReason"
          :rows="6"
          placeholder="Please provide a detailed rejection reason to help the instructor improve the course..."
          :maxlength="500"
          show-count
        />
      </a-form-item>
    </a-modal>
  </a-drawer>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { approveCourseApi } from '@/api/admin/approveCourseApi'
import { notification } from 'ant-design-vue'
import type { Course } from '@/types/Course'
import {
  CheckCircleOutlined,
  CloseCircleOutlined,
  UserOutlined,
  BookOutlined,
  GlobalOutlined,
  CalendarOutlined
} from '@ant-design/icons-vue'
import dayjs from 'dayjs'

const props = defineProps<{
  visible: boolean
  course: Course | null
}>()

const emit = defineEmits(['update:visible', 'refresh'])

const loadingApprove = ref(false)
const loadingReject = ref(false)
const rejectModalVisible = ref(false)
const rejectReason = ref('')

const handleClose = () => {
  emit('update:visible', false)
  rejectReason.value = ''
  rejectModalVisible.value = false
}

const approveCourse = async () => {
  if (!props.course) return

  loadingApprove.value = true
  try {
    await approveCourseApi.approveCourse(props.course.id)
    notification.success({
      message: 'Approved successfully!',
      description: `The course "${props.course.title}" has been approved.`
    })
    emit('refresh')
    handleClose()
  } catch (e: any) {
    notification.error({
      message: 'Error',
      description: e.message || 'Approval failed.'
    })
  } finally {
    loadingApprove.value = false
  }
}

const openRejectModal = () => {
  rejectReason.value = ''
  rejectModalVisible.value = true
}

const rejectCourse = async () => {
  if (!props.course) return

  if (!rejectReason.value.trim()) {
    notification.warning({
      message: 'Missing information',
      description: 'Please provide a rejection reason.'
    })
    return
  }

  loadingReject.value = true
  try {
    await approveCourseApi.rejectCourse(props.course.id, rejectReason.value)

    notification.success({
      message: 'Rejected!',
      description: `The course "${props.course.title}" has been rejected.`
    })

    rejectModalVisible.value = false
    emit('refresh')
    handleClose()
  } catch (e: any) {
    notification.error({
      message: 'Error',
      description: e.message || 'Rejection failed.'
    })
  } finally {
    loadingReject.value = false
  }
}

const getInitials = (name?: string) => {
  if (!name) return '?'
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
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

const getRoleColor = (role: string) => {
  switch (role) {
    case 'admin': return 'blue'
    case 'instructor': return 'purple'
    case 'student': return 'cyan'
    default: return 'gray'
  }
}

const getRoleLabel = (role: string) => {
  switch (role) {
    case 'admin': return 'Admin'
    case 'instructor': return 'Instructor'
    case 'student': return 'Student'
    default: return role
  }
}

const formatPrice = (price: number | undefined) => {
  if (!price) return '$0'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price)
}

const formatDate = (dateString: string) => {
  return dayjs(dateString).format('DD/MM/YYYY')
}
</script>
