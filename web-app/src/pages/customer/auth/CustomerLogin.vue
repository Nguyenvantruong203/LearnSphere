<template>
  <LayoutLoginUser>
    <div class="w-full">
      <!-- Logo + tagline -->
      <div class="text-center mb-6">
        <img :src="logoUrl" class="mx-auto h-16 mb-2" />
      </div>

      <!-- Segmented -->
      <div class="text-center text-white">
        <div class="mx-auto py-[10px] px-4 inline-flex gap-3 rounded-full bg-green-60">
          <button :class="[
            'px-6 py-2 rounded-full font-medium transition-colors',
            tab === 'login' ? 'bg-green shadow bg-green' : 'text-teal-800/80 hover:bg-green-60'
          ]" @click="tab = 'login'">
            <p class="text-base">Login</p>
          </button>
          <button :class="[
            'px-6 py-2 rounded-full font-medium transition-colors',
            tab === 'register' ? 'bg-green shadow bg-green' : 'text-teal-800/80 hover:bg-green-60'
          ]" @click="tab = 'register'">
            <p class="text-base">Register</p>
          </button>
        </div>
      </div>

      <!-- Form -->
      <div class="flex justify-center items-center">
        <FormLogin v-if="tab === 'login'" v-model:email="email" v-model:password="password" :loading="loading"
          :tab="tab" @submit="onSubmit" />
        <FormRegister v-if="tab === 'register'" v-model:email="email" v-model:password="password" v-model:username="username" :loading="loading"
          :tab="tab" @submit="onSubmit" />
      </div>
    </div>
  </LayoutLoginUser>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { notification } from 'ant-design-vue'
import LayoutLoginUser from '@/pages/customer/layout/layoutLoginUser.vue'
import FormLogin from '@/components/customer/auth/FormLogin.vue'
import FormRegister from '@/components/customer/auth/FormRegister.vue'
import logoUrl from '@/assets/images/logo.png'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const email = ref('')
const password = ref('')
const username = ref('')
const loading = ref(false)
const tab = ref<'login' | 'register'>('login')

// Load tab from localStorage when component is mounted
onMounted(() => {
  const savedTab = localStorage.getItem('auth_tab')
  if (savedTab && (savedTab === 'login' || savedTab === 'register')) {
    tab.value = savedTab
  }
})

// Watch for changes in tab and save to localStorage
watch(tab, (newTab) => {
  localStorage.setItem('auth_tab', newTab)
})

const onSubmit = async () => {
  loading.value = true
  try {
    if (tab.value === 'login') {
      await authStore.login({ email: email.value, password: password.value })
      notification.success({
        message: 'Thành công',
        description: 'Đăng nhập thành công!'
      })
    } else {
      await authStore.register({
        username: username.value,
        email: email.value,
        password: password.value,
        password_confirmation: password.value // Assuming confirmation is the same for simplicity
      })
      notification.success({
        message: 'Thành công',
        description: 'Đăng ký thành công!'
      })
    }
  } catch (e: any) {
    notification.error({
      message: 'Thất bại',
      description: e?.message ?? (tab.value === 'login' ? 'Đăng nhập thất bại' : 'Đăng ký thất bại')
    })
  } finally {
    loading.value = false
  }
}
</script>
