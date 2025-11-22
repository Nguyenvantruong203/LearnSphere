<template>
  <section class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">

      <!-- Header Section -->
      <div class="text-center mb-12">
        <div
          class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mb-6 shadow-lg animate-bounce-slow">
          <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M12,2L13.09,8.26L20,9L13.09,9.74L12,16L10.91,9.74L4,9L10.91,8.26L12,2ZM8,21L9.5,16L13,16L11.5,21L8,21Z" />
          </svg>
        </div>

        <h1
          class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
          My Certificates
        </h1>

        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Your collection of course completion certificates from LearnSphere
        </p>

        <div class="w-24 h-1 bg-gradient-to-r from-amber-500 to-orange-500 mx-auto mt-6"></div>
      </div>

      <!-- Stats Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div
          class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
          <div class="flex items-center">
            <div
              class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-800">{{ certificates.length }}</p>
              <p class="text-gray-600 text-sm">Total Certificates</p>
            </div>
          </div>
        </div>

        <div
          class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
          <div class="flex items-center">
            <div
              class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3H19M18.5,18.5V13.2A3.26,3.26 0 0,0 15.24,9.94C14.39,9.94 13.4,10.46 12.92,11.24V10.13H10.13V18.5H12.92V13.57C12.92,12.8 13.54,12.17 14.31,12.17A1.4,1.4 0 0,1 15.71,13.57V18.5H18.5M6.88,8.56A1.68,1.68 0 0,0 8.56,6.88C8.56,5.95 7.81,5.19 6.88,5.19A1.69,1.69 0 0,0 5.19,6.88C5.19,7.81 5.95,8.56 6.88,8.56M8.27,18.5V10.13H5.5V18.5H8.27Z" />
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-800">{{ completedCourses }}</p>
              <p class="text-gray-600 text-sm">Completed Courses</p>
            </div>
          </div>
        </div>

        <div
          class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
          <div class="flex items-center">
            <div
              class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z" />
              </svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-800">{{ achievementRate }}%</p>
              <p class="text-gray-600 text-sm">Achievement Rate</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block">
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full animate-spin mb-6 relative">
            <div class="absolute inset-2 bg-white rounded-full"></div>
          </div>
          <p class="text-gray-500 text-lg animate-pulse">Loading your certificates...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="certificates.length === 0" class="text-center py-20">
        <div class="mb-8">
          <div
            class="w-32 h-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full mx-auto flex items-center justify-center mb-6">
            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-700 mb-4">No Certificates Yet</h3>
          <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
            You haven't completed any courses yet. Start learning and earn your first certificate!
          </p>
          <a-button type="primary" size="large"
            class="bg-gradient-to-r from-blue-500 to-purple-600 border-0 px-8 h-12 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
              </svg>
              Explore Courses
            </div>
          </a-button>
        </div>
      </div>

      <!-- Certificates Grid/List -->
      <div v-else>
        <!-- Filter & Sort Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
          <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">My Certificates</h2>
            <p class="text-gray-600">{{ certificates.length }} certificate{{ certificates.length !== 1 ? 's' : '' }}
              found</p>
          </div>

          <div class="flex gap-3">
            <a-button :type="viewMode === 'grid' ? 'primary' : 'default'" size="large" @click="viewMode = 'grid'"
              class="w-12" title="Grid View">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3,11H11V3H3M3,21H11V13H3M13,21H21V13H13M13,3V11H21V3" />
              </svg>
            </a-button>

            <a-button :type="viewMode === 'list' ? 'primary' : 'default'" size="large" @click="viewMode = 'list'"
              class="w-12" title="List View">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9,5V9H21V5M9,19H21V15H9M9,14H21V10H9M4,9H8V5H4M4,19H8V15H4M4,14H8V10H4V14Z" />
              </svg>
            </a-button>
          </div>
        </div>

        <!-- Certificates Display -->
        <div :class="[
          'transition-all duration-300',
          viewMode === 'grid'
            ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'
            : 'space-y-4'
        ]">
          <CertificateCard v-for="(item, index) in sortedCertificates" :key="item.id" :certificate="item"
            :viewMode="viewMode" :index="index" @view="openCertificate(item.id)" />
        </div>
      </div>
    </div>

    <!-- Certificate Detail Modal -->
    <CertificateModal v-model:open="isModalOpen" :certificate="modalData" />
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { notification } from 'ant-design-vue'
import { certificationApi } from '@/api/customer/certificationApi'
import CertificateCard from '@/components/customer/certification/CertificateCard.vue'
import CertificateModal from '@/components/customer/certification/CertificateModal.vue'

const certificates = ref([])
const loading = ref(true)
const sortBy = ref('newest')
const viewMode = ref('grid')

const isModalOpen = ref(false)
const modalData = ref(null)

// Computed stats
const completedCourses = computed(() => {
  return [...new Set(certificates.value.map(cert => cert.course?.id))].length
})

const achievementRate = computed(() => {
  if (certificates.value.length === 0) return 0
  return Math.round((certificates.value.length / completedCourses.value) * 100)
})

// Sorted certificates
const sortedCertificates = computed(() => {
  const sorted = [...certificates.value]

  switch (sortBy.value) {
    case 'newest':
      return sorted.sort((a, b) => new Date(b.issued_at) - new Date(a.issued_at))
    case 'oldest':
      return sorted.sort((a, b) => new Date(a.issued_at) - new Date(b.issued_at))
    case 'course':
      return sorted.sort((a, b) => a.course?.title.localeCompare(b.course?.title || '') || '')
    default:
      return sorted
  }
})

onMounted(async () => {
  try {
    const res = await certificationApi.getMyCertificates()
    certificates.value = res.certificates || []
  } catch (e) {
    notification.error({
      message: 'Failed to load certificates',
      description: 'Please try again later.',
      duration: 4
    })
  } finally {
    loading.value = false
  }
})

const openCertificate = async (id) => {
  try {
    const res = await certificationApi.getCertificateDetail(id)
    modalData.value = res.certificate
    isModalOpen.value = true
  } catch {
    notification.error({
      message: 'Error loading certificate',
      description: 'Unable to load certificate details.',
      duration: 3
    })
  }
}
</script>

<style scoped>
@keyframes bounceSlow {

  0%,
  20%,
  50%,
  80%,
  100% {
    transform: translateY(0);
  }

  40% {
    transform: translateY(-8px);
  }

  60% {
    transform: translateY(-4px);
  }
}

.animate-bounce-slow {
  animation: bounceSlow 3s ease-in-out infinite;
}

@media (max-width: 640px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>