import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api/authApi'
import type { User } from '@/types/user'
import type { LoginPayload, RegisterPayload } from '@/api/authApi'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))

  const isLoggedIn = computed(() => !!token.value && !!user.value)

  function setUser(newUser: User | null) {
    user.value = newUser
    if (newUser) {
      localStorage.setItem('auth_user', JSON.stringify(newUser))
    } else {
      localStorage.removeItem('auth_user')
    }
  }

  function setToken(newToken: string | null) {
    token.value = newToken
    if (newToken) {
      localStorage.setItem('auth_token', newToken)
    } else {
      localStorage.removeItem('auth_token')
    }
  }

  async function init() {
    if (token.value) {
      try {
        const rawUser = localStorage.getItem('auth_user')
        if (rawUser) {
          setUser(JSON.parse(rawUser))
        } else {
          // Optionally, you could fetch user data from API here if you have an endpoint
          // For now, we'll just clear the token if user data is missing
          logout()
        }
      } catch (error) {
        console.error('Failed to initialize auth store:', error)
        logout()
      }
    }
  }

  async function login(payload: LoginPayload) {
    try {
      const response = await authApi.login(payload)
      setUser(response.user)
      setToken(response.token)
      await router.push('/')
    } catch (error) {
      console.error('Login failed:', error)
      throw error
    }
  }

  async function register(payload: RegisterPayload) {
    try {
      const response = await authApi.register(payload)
      setUser(response.user)
      setToken(response.token)
      await router.push('/')
    } catch (error) {
      console.error('Registration failed:', error)
      throw error
    }
  }
  async function logout() {
    if (token.value) {
      try {
        await authApi.logout(token.value)
      } catch (error) {
        console.error('API logout failed, logging out client-side anyway.', error)
      }
    }
    setUser(null)
    setToken(null)
    await router.push('/login')
  }

  init()

  return {
    user,
    token,
    isLoggedIn,
    login,
    register,
    logout
  }
})
