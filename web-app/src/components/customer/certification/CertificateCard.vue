<template>
  <!-- Grid View -->
  <div 
    v-if="viewMode === 'grid'"
    class="group bg-white shadow-lg rounded-2xl p-6 cursor-pointer hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 animate-fade-in"
    :style="{ animationDelay: `${index * 100}ms` }"
    @click="$emit('view')"
  >
    <!-- Certificate Icon -->
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-orange-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12,2L13.09,8.26L20,9L13.09,9.74L12,16L10.91,9.74L4,9L10.91,8.26L12,2ZM8,21L9.5,16L13,16L11.5,21L8,21Z"/>
        </svg>
      </div>
      
      <div class="text-right">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
          </svg>
          Completed
        </span>
      </div>
    </div>

    <!-- Course Title -->
    <h3 class="font-bold text-lg text-gray-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
      {{ certificate.course.title }}
    </h3>

    <!-- Certificate Details -->
    <div class="space-y-2 mb-5">
      <div class="flex items-center text-gray-600 text-sm">
        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
        </svg>
        <span class="font-medium">{{ certificate.certificate_code }}</span>
      </div>
      
      <div class="flex items-center text-gray-600 text-sm">
        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3H19M5,5V7H19V5H5M5,9V11H19V9H5M5,13V15H19V13H5M5,17V19H19V17H5Z"/>
        </svg>
        <span>{{ formatDate(certificate.issued_at) }}</span>
      </div>
      
      <div v-if="certificate.course?.instructor" class="flex items-center text-gray-600 text-sm">
        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
        </svg>
        <span>{{ certificate.course.instructor.name }}</span>
      </div>
    </div>

    <!-- View Button -->
    <button class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105 group-hover:-translate-y-1">
      <span class="flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
        </svg>
        View Certificate
      </span>
    </button>
  </div>

  <!-- List View -->
  <div 
    v-else
    class="group bg-white shadow-lg rounded-2xl p-6 cursor-pointer hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 animate-slide-in"
    :style="{ animationDelay: `${index * 50}ms` }"
    @click="$emit('view')"
  >
    <div class="flex items-center gap-6">
      <!-- Certificate Icon -->
      <div class="w-16 h-16 bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12,2L13.09,8.26L20,9L13.09,9.74L12,16L10.91,9.74L4,9L10.91,8.26L12,2ZM8,21L9.5,16L13,16L11.5,21L8,21Z"/>
        </svg>
      </div>

      <!-- Certificate Content -->
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between">
          <div class="flex-1 pr-4">
            <h3 class="font-bold text-xl text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-200">
              {{ certificate.course.title }}
            </h3>
            
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                </svg>
                <span class="font-medium">{{ certificate.certificate_code }}</span>
              </div>
              
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3H19M5,5V7H19V5H5M5,9V11H19V9H5M5,13V15H19V13H5M5,17V19H19V17H5Z"/>
                </svg>
                <span>{{ formatDate(certificate.issued_at) }}</span>
              </div>
              
              <div v-if="certificate.course?.instructor" class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                </svg>
                <span>{{ certificate.course.instructor.name }}</span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-4 flex-shrink-0">
            <!-- Status Badge -->
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
              </svg>
              Completed
            </span>

            <!-- View Button -->
            <button class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                </svg>
                View
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  certificate: {
    type: Object,
    required: true
  },
  viewMode: {
    type: String,
    default: 'grid'
  },
  index: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['view'])

// Format date in a clean, international style
const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>

<style scoped>
/* Fade-in animation for grid */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Slide-in animation for list */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out forwards;
  opacity: 0;
}

.animate-slide-in {
  animation: slideIn 0.6s ease-out forwards;
  opacity: 0;
}

/* Two-line text clamp */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:-translate-y-1 {
  transform: translateY(-4px);
}

.group:hover .group-hover\:text-blue-600 {
  color: #2563eb;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .line-clamp-2 {
    -webkit-line-clamp: 1;
  }
}
</style>