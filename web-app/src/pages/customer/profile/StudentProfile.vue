<template>
  <LayoutHomepage>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Personal Information</h1>
          <p class="text-gray-600">Update your personal details and profile picture</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Avatar Section -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h2>

              <!-- Avatar Display -->
              <div class="flex flex-col items-center">
                <div class="relative mb-4">
                  <a-avatar :size="120" :src="currentUser?.avatar_url || undefined" class="border-4 border-gray-200">
                    <template #icon>
                      <UserOutlined />
                    </template>
                  </a-avatar>

                  <!-- Upload Button Overlay -->
                  <div
                    class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer"
                    @click="triggerFileInput">
                    <CameraOutlined class="text-white text-xl" />
                  </div>
                </div>

                <!-- Hidden File Input -->
                <input ref="fileInputRef" type="file" accept="image/*" class="hidden" @change="handleAvatarUpload" />

                <!-- Upload Button -->
                <a-button type="primary" :loading="avatarUploading" @click="triggerFileInput">
                  <UploadOutlined />
                  Change Avatar
                </a-button>

                <p class="text-sm text-gray-500 mt-2">
                  JPG, PNG – up to 2MB
                </p>
              </div>
            </div>
          </div>

          <!-- Profile Form -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-6">Profile Details</h2>

              <a-form :model="profileForm" :rules="formRules" layout="vertical" @finish="handleUpdateProfile">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Full Name -->
                  <div class="md:col-span-2">
                    <a-form-item name="name" label="Full Name">
                      <a-input v-model:value="profileForm.name" placeholder="Enter your full name"
                        :disabled="profileUpdating" />
                    </a-form-item>
                  </div>

                  <!-- Email (Read-only) -->
                  <div class="md:col-span-2">
                    <a-form-item label="Email">
                      <a-input :value="currentUser?.email" disabled class="bg-gray-50" />
                    </a-form-item>
                  </div>

                  <!-- Phone -->
                  <a-form-item name="phone" label="Phone Number">
                    <a-input v-model:value="profileForm.phone" placeholder="Enter your phone number"
                      :disabled="profileUpdating" />
                  </a-form-item>

                  <!-- Gender -->
                  <a-form-item name="gender" label="Gender">
                    <a-select v-model:value="profileForm.gender" placeholder="Select gender"
                      :disabled="profileUpdating">
                      <a-select-option value="male">Male</a-select-option>
                      <a-select-option value="female">Female</a-select-option>
                      <a-select-option value="other">Other</a-select-option>
                    </a-select>
                  </a-form-item>

                  <!-- Birth Date -->
                  <a-form-item name="birth_date" label="Date of Birth">
                    <a-date-picker v-model:value="profileForm.birth_date" placeholder="Select date of birth"
                      format="DD/MM/YYYY" :disabled="profileUpdating" class="w-full" />
                  </a-form-item>

                  <!-- Address -->
                  <div class="md:col-span-2">
                    <a-form-item name="address" label="Address">
                      <a-textarea v-model:value="profileForm.address" placeholder="Enter your address" :rows="3"
                        :disabled="profileUpdating" />
                    </a-form-item>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-8">
                  <a-button type="primary" html-type="submit" :loading="profileUpdating" size="large">
                    Update Profile
                  </a-button>
                </div>
              </a-form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </LayoutHomepage>
</template>

<script setup lang="ts">
import { reactive, ref, computed, onMounted } from 'vue'
import { notification } from 'ant-design-vue'
import { UserOutlined, CameraOutlined, UploadOutlined } from '@ant-design/icons-vue'
import dayjs, { type Dayjs } from 'dayjs'
import LayoutHomepage from '../layout/layoutHomepage.vue'
import { useClientAuthStore } from '@/stores/clientAuth'
import { profileApi } from '@/api/customer/profileApi'
import type { User } from '@/types/User'

// Store
const authStore = useClientAuthStore()

// Computed
const currentUser = computed(() => authStore.user)

// Reactive form state
const profileForm = reactive({
  name: '',
  phone: '',
  address: '',
  birth_date: undefined as Dayjs | undefined,
  gender: undefined as 'male' | 'female' | 'other' | undefined
})

// Loading states
const profileUpdating = ref(false)
const avatarUploading = ref(false)

// Refs
const fileInputRef = ref<HTMLInputElement>()

// Validation rules
const formRules = {
  name: [
    { required: true, message: 'Please enter your full name' },
    { min: 2, message: 'Name must be at least 2 characters long' },
    { max: 100, message: 'Name must not exceed 100 characters' }
  ],
  phone: [
    { pattern: /^[0-9+\-\s()]+$/, message: 'Invalid phone number format' }
  ]
}

// Initialize form values
const initializeForm = () => {
  if (currentUser.value) {
    profileForm.name = currentUser.value.name || ''
    profileForm.phone = currentUser.value.phone || ''
    profileForm.address = currentUser.value.address || ''
    profileForm.gender = currentUser.value.gender

    if (currentUser.value.birth_date) {
      profileForm.birth_date = dayjs(currentUser.value.birth_date)
    }
  }
}

// Update profile
const handleUpdateProfile = async () => {
  if (!currentUser.value) return

  profileUpdating.value = true

  try {
    const updateData: any = {
      name: profileForm.name.trim()
    }

    if (profileForm.phone?.trim()) updateData.phone = profileForm.phone.trim()
    if (profileForm.address?.trim()) updateData.address = profileForm.address.trim()
    if (profileForm.gender) updateData.gender = profileForm.gender
    if (profileForm.birth_date) updateData.birth_date = profileForm.birth_date.format('YYYY-MM-DD')

    const updatedUser = await profileApi.updateProfile(updateData)
    authStore.setUser(updatedUser)

    notification.success({
      message: 'Profile Updated',
      description: 'Your personal information has been successfully updated.'
    })
  } catch (error: any) {
    console.error('Profile update error:', error)
    notification.error({
      message: 'Update Failed',
      description: error?.message || 'An error occurred while updating your profile.'
    })
  } finally {
    profileUpdating.value = false
  }
}

// Avatar upload handlers
const triggerFileInput = () => {
  fileInputRef.value?.click()
}

const handleAvatarUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]

  if (!file) return

  // Validate file type and size
  if (!file.type.startsWith('image/')) {
    notification.error({
      message: 'Invalid File',
      description: 'Please select an image file (JPG, PNG, GIF).'
    })
    return
  }

  if (file.size > 2 * 1024 * 1024) {
    notification.error({
      message: 'File Too Large',
      description: 'The file size must not exceed 2MB.'
    })
    return
  }

  avatarUploading.value = true

  try {
    const formData = new FormData()
    formData.append('avatar', file)

    const updatedUser = await profileApi.updateAvatar(formData)
    authStore.setUser(updatedUser)

    notification.success({
      message: 'Avatar Updated',
      description: 'Your profile picture has been successfully updated.'
    })
  } catch (error: any) {
    console.error('Avatar upload error:', error)
    notification.error({
      message: 'Upload Failed',
      description: error?.message || 'An error occurred while uploading the avatar.'
    })
  } finally {
    avatarUploading.value = false
    if (target) target.value = ''
  }
}

// Lifecycle
onMounted(() => {
  initializeForm()
})
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
