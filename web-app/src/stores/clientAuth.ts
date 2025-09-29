import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api/authApi'
import type { User } from '@/types/user'
import type { LoginPayload, RegisterPayload } from '@/types/user'
import { useRouter } from 'vue-router'

export const useClientAuthStore = defineStore(
  'clientAuth',
  () => {
    const router = useRouter()
    const user = ref<User | null>(null)
    const token = ref<string | null>(null)

    const isLoggedIn = computed(() => !!token.value && !!user.value)

    function setUser(newUser: User | null) {
      user.value = newUser ? { ...newUser } : null
    }

    function setToken(newToken: string | null) {
      token.value = newToken
    }

    async function login(payload: LoginPayload): Promise<User> {
      const response = await authApi.login(payload)

      if (!response.user || !response.access_token) {
        throw new Error('Invalid API response during login.')
      }

      setToken(response.access_token)
      setUser(response.user)

      return response.user
    }

    async function loginWithGoogle(code: string): Promise<User> {
      const response = await authApi.handleGoogleCallback(code)

      if (!response.user || !response.access_token) {
        throw new Error('Invalid API response during Google login.')
      }

      setToken(response.access_token)
      setUser(response.user)

      return response.user
    }

    async function register(payload: RegisterPayload) {
      await authApi.register(payload)
      await router.push('/login?registered=true')
    }

    async function logout() {
      try {
        await authApi.logout()
      } catch (error) {
        console.error('API logout failed, logging out client-side anyway.', error)
      }
      setUser(null)
      setToken(null)
      await router.push('/login')
    }

    return {
      user,
      token,
      isLoggedIn,
      login,
      loginWithGoogle,
      register,
      logout,
      setUser,
      setToken,
    }
  },
  {
    persist: { key: 'client_auth' }, // 👈 key riêng cho client
  },
)
