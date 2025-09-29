<template>
  <LayoutAdmin>
    <HeaderAdmin>
      <a-breadcrumb>
        <a-breadcrumb-item>
          <span class="text-gray-400">Pages</span>
        </a-breadcrumb-item>
        <a-breadcrumb-item>
          <span class="text-gray-700 font-bold">Profile</span>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </HeaderAdmin>

    <div class="bg-white rounded-2xl overflow-hidden">
      <!-- Header -->
      <div class="bg-white px-8 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
          </div>
          <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
            <span>Active</span>
          </div>
        </div>
      </div>

      <div v-if="initialLoading" class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Avatar Skeleton -->
          <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 animate-pulse">
              <div class="flex justify-center mb-6">
                <a-skeleton-avatar active :size="128" shape="circle" />
              </div>
              <a-skeleton-button active class="w-4/5 rounded-full" />
              <div class="mt-4 space-y-2">
                <a-skeleton-input active style="width: 60%;" :paragraph="{ rows: 2 }" />
              </div>
            </div>
          </div>
          <!-- Form Skeleton -->
          <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8 animate-pulse">
              <div class="md:col-span-2">
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
              <div class="md:col-span-2">
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
              <div>
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
              <div>
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
              <div class="md:col-span-2">
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
              <div class="md:col-span-2">
                <a-skeleton-input active style="width: 100%; height: 50px;" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="p-8">
        <a-form layout="vertical" :model="formState" @finish="handleFinish"
          class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 h-full">
              <!-- Vertical stack + center align -->
              <div class="flex flex-col items-center text-center space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Avatar</h3>

                <a-upload v-model:file-list="fileList" name="avatar" :show-upload-list="false"
                  :before-upload="beforeUpload" class="block">
                  <div
                    class="w-32 h-32 rounded-full overflow-hidden shadow-lg ring-4 ring-white relative group cursor-pointer">
                    <img v-if="imageUrl" :src="imageUrl" alt="avatar"
                      class="w-full h-full object-cover transition-all duration-300 group-hover:brightness-75" />
                    <div v-else
                      class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex flex-col items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-300">
                      <loading-outlined v-if="loadingAvatar" class="text-2xl text-blue-500"></loading-outlined>
                      <plus-outlined v-else class="text-2xl text-blue-500"></plus-outlined>
                      <div class="mt-2 text-xs text-blue-600 font-medium">Upload</div>
                    </div>

                    <!-- Hover overlay -->
                    <div
                      class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                      <upload-outlined
                        class="text-white text-xl opacity-0 group-hover:opacity-100 transition-all duration-300" />
                    </div>
                  </div>
                </a-upload>

                <a-button @click="triggerUpload" :loading="loadingAvatar"
                  class="bg-btn-admin border-0 text-white hover:from-blue-600 hover:to-indigo-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                  <upload-outlined class="mr-2" /> <span>Change Avatar</span>
                </a-button>

                <input type="file" ref="uploadInput" @change="handleAvatarChange" accept="image/png, image/jpeg" hidden>
              </div>
            </div>
          </div>
          <!-- Form Fields -->
          <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
              <a-form-item label="Full Name" name="name"
                :rules="[{ required: true, message: 'Please enter your full name!' }]" class="md:col-span-2">
                <a-input v-model:value="formState.name" size="large" placeholder="Enter your full name"
                  class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-base" />
              </a-form-item>

              <a-form-item label="Email" name="email"
                :rules="[{ required: true, message: 'Please enter your email!' }, { type: 'email', message: 'Invalid email format!' }]"
                class="md:col-span-2">
                <a-input v-model:value="formState.email" disabled size="large"
                  class="rounded-lg border-gray-300 bg-gray-50 text-base" />
                <div class="mt-2 text-sm text-gray-500 flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                  </svg>
                  Email cannot be changed for security reasons
                </div>
              </a-form-item>

              <a-form-item label="Phone Number" name="phone">
                <a-input v-model:value="formState.phone" size="large" placeholder="Enter your phone number"
                  class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-base" />
              </a-form-item>

              <a-form-item label="Date of Birth" name="birth_date">
                <a-date-picker v-model:value="formState.birth_date" size="large" placeholder="Select your date of birth"
                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-base"
                  format="YYYY-MM-DD" />
              </a-form-item>

              <a-form-item label="Address" name="address" class="md:col-span-2">
                <a-input v-model:value="formState.address" size="large" placeholder="Enter your address"
                  class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-base" />
              </a-form-item>

              <a-form-item label="Gender" name="gender" class="md:col-span-2">
                <a-select v-model:value="formState.gender" placeholder="Select your gender" size="large">
                  <a-select-option value="male">Male</a-select-option>
                  <a-select-option value="female">Female</a-select-option>
                  <a-select-option value="other">Other</a-select-option>
                </a-select>
              </a-form-item>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end sm:flex-row gap-4">
              <a-button type="primary" html-type="submit" :loading="loading"
                class="bg-btn-admin border-0 rounded-full text-base font-medium hover:bg-green-600 shadow-lg hover:shadow-xl transition-all duration-300 flex-1 sm:flex-initial flex justify-center items-center">
                <template v-if="!loading">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </template>
                <span>Save Changes</span>
              </a-button>
            </div>
          </div>
        </a-form>
      </div>
    </div>
  </LayoutAdmin>

</template>

<script setup lang="ts">
import { ref, reactive, watchEffect } from 'vue';
import { useClientAuthStore } from '@/stores/clientAuth';
import { userApi } from '@/api/admin/userApi';
import { notification, Upload as AUpload, Form as AForm, Input as AInput, Button as AButton, FormItem as AFormItem, DatePicker as ADatePicker, Select as ASelect, SelectOption as ASelectOption, SkeletonInput as ASkeletonInput, SkeletonAvatar as ASkeletonAvatar, SkeletonButton as ASkeletonButton } from 'ant-design-vue';
import { PlusOutlined, LoadingOutlined, UploadOutlined } from '@ant-design/icons-vue';
import LayoutAdmin from '../layout/LayoutAdmin.vue';
import dayjs from 'dayjs';

const authStore = useClientAuthStore();
const initialLoading = ref(true);

const formState = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  birth_date: undefined as dayjs.Dayjs | undefined,
  gender: '',
  avatar_url: '',
});

const originalState = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  birth_date: undefined as dayjs.Dayjs | undefined,
  gender: '',
  avatar_url: '',
});

const loading = ref(false);
const loadingAvatar = ref(false);
const imageUrl = ref<string | undefined>('');
const fileList = ref([]);
const uploadInput = ref<HTMLInputElement | null>(null);

watchEffect(() => {
  if (authStore.user) {
    // Data is available, populate form
    formState.name = authStore.user.name;
    formState.email = authStore.user.email;
    formState.phone = authStore.user.phone || '';
    formState.address = authStore.user.address || '';
    formState.birth_date = authStore.user.birth_date ? dayjs(authStore.user.birth_date) : undefined;
    formState.gender = authStore.user.gender || '';

    originalState.name = authStore.user.name;
    originalState.email = authStore.user.email;
    originalState.phone = authStore.user.phone || '';
    originalState.address = authStore.user.address || '';
    originalState.birth_date = authStore.user.birth_date ? dayjs(authStore.user.birth_date) : undefined;
    originalState.gender = authStore.user.gender || '';

    imageUrl.value = authStore.user.avatar_url || '';

    setTimeout(() => {
      initialLoading.value = false;
    }, 500);
  } else {
    initialLoading.value = true;
  }
});

const handleFinish = async (values: any) => {
  loading.value = true;
  try {
    const payload = {
      ...values,
      birth_date: values.birth_date ? values.birth_date.format('YYYY-MM-DD') : null,
    };
    const updatedUser = await userApi.updateProfile(payload)
    authStore.setUser(updatedUser)

    formState.name = updatedUser.name;
    formState.phone = updatedUser.phone || '';
    formState.address = updatedUser.address || '';
    formState.birth_date = updatedUser.birth_date ? dayjs(updatedUser.birth_date) : undefined;
    formState.gender = updatedUser.gender || '';

    Object.assign(originalState, {
      name: updatedUser.name,
      phone: updatedUser.phone || '',
      address: updatedUser.address || '',
      birth_date: updatedUser.birth_date ? dayjs(updatedUser.birth_date) : undefined,
      gender: updatedUser.gender || '',
    });

    notification.success({ message: 'Profile updated successfully!' });
  } catch (error: any) {
    notification.error({ message: error.message || 'Update failed.' });
  } finally {
    loading.value = false;
  }
};

const triggerUpload = () => {
  uploadInput.value?.click();
}

const handleAvatarChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    // `beforeUpload` now handles validation, preview, and triggering the upload.
    beforeUpload(file);
  }
};

const uploadAvatar = async (file: File) => {
  const formData = new FormData();
  formData.append('avatar', file);

  loadingAvatar.value = true;
  try {
    const updatedUser = await userApi.updateAvatar(formData)
    authStore.setUser(updatedUser)
    imageUrl.value = updatedUser.avatar_url || ''
    notification.success({ message: 'Avatar updated successfully!' });
  } catch (error: any) {
    notification.error({ message: error.message || 'Avatar update failed.' });
  } finally {
    loadingAvatar.value = false;
  }
}

const beforeUpload = (file: File) => {
  const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
  if (!isJpgOrPng) {
    notification.error({ message: 'You can only upload JPG/PNG file!' });
    return false;
  }
  const isLt2M = file.size / 1024 / 1024 < 2;
  if (!isLt2M) {
    notification.error({ message: 'Image must be smaller than 2MB!' });
    return false;
  }

  const reader = new FileReader();
  reader.onload = e => {
    if (e.target?.result) {
      imageUrl.value = e.target.result as string;
    }
  };
  reader.readAsDataURL(file);

  // Trigger the upload manually
  uploadAvatar(file);

  // Prevent ant-design-vue's default upload behavior by returning false
  return false;
};
</script>
