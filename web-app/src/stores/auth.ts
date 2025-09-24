import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api/authApi'
import type { User } from '@/types/user'
import type { LoginPayload, RegisterPayload } from '@/api/authApi'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()
  const storedUser = localStorage.getItem('auth_user')
  const user = ref<User | null>(storedUser ? JSON.parse(storedUser) : null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))

  const isLoggedIn = computed(() => !!token.value && !!user.value)

  function setUser(newUser: User | null) {
    if (newUser) {
      // If user.value exists, merge properties to preserve reactivity.
      // Otherwise, assign the new object.
      if (user.value) {
        Object.assign(user.value, newUser);
      } else {
        user.value = newUser;
      }
      localStorage.setItem('auth_user', JSON.stringify(user.value));
    } else {
      user.value = null;
      localStorage.removeItem('auth_user');
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

  async function login(payload: LoginPayload): Promise<User> {
    try {
      const response = await authApi.login(payload)
      
      if (!response.user || !response.access_token) {
        throw new Error('Invalid API response during login.')
      }

      setToken(response.access_token)
      setUser(response.user)
      
      return response.user
    } catch (error) {
      console.error('Login failed:', error)
      // Clear state on failure
      setToken(null)
      setUser(null)
      throw error
    }
  }

  async function register(payload: RegisterPayload) {
    try {
      // Registration in this flow doesn't log the user in directly.
      // It sends a verification email.
      await authApi.register(payload)
      // Redirect to a page informing the user to check their email.
      await router.push('/login?registered=true')
    } catch (error) {
      console.error('Registration failed:', error)
      throw error
    }
  }
  async function logout() {
    if (token.value) {
      try {
        await authApi.logout() // API call doesn't need the token passed as it uses the cookie/header
      } catch (error) {
        console.error('API logout failed, logging out client-side anyway.', error)
      }
    }
    setUser(null)
    setToken(null)
    // Ensure localStorage is cleared
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
    await router.push('/login')
  }

  return {
    user,
    token,
    isLoggedIn,
    login,
    register,
    logout,
    setUser
  }
})
