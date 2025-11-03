import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api/authApi'
import type { User, LoginPayload } from '@/types/User'
import { useRouter } from 'vue-router'

export const useAdminAuthStore = defineStore(
  'adminAuth',
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

    /**
     * 🔹 Đăng nhập — kiểm tra vai trò rồi lưu vào localStorage đúng key
     */
    async function login(payload: LoginPayload): Promise<User> {
      const response = await authApi.login(payload)

      if (!response.user || !response.access_token) {
        throw new Error('Invalid API response during login.')
      }

      setToken(response.access_token)
      setUser(response.user)

      // 🔹 Xác định key lưu theo vai trò
      const storageKey =
        response.user.role === 'admin'
          ? 'admin_auth'
          : response.user.role === 'instructor'
          ? 'instructor_auth'
          : 'client_auth'

      // 🔹 Lưu thủ công vào localStorage để Echo dùng chính xác
      localStorage.setItem(
        storageKey,
        JSON.stringify({
          token: response.access_token,
          user: response.user,
        }),
      )

      return response.user
    }

    async function logout() {
      try {
        await authApi.logout()
      } catch (error) {
        console.error('API logout failed, logging out client-side anyway.', error)
      }
      setUser(null)
      setToken(null)

      // 🔹 Xóa cả 2 loại auth khi logout
      localStorage.removeItem('admin_auth')
      localStorage.removeItem('instructor_auth')

      await router.push('/admin/login')
    }

    return {
      user,
      token,
      isLoggedIn,
      login,
      logout,
      setUser,
      setToken,
    }
  },
  {
    persist: { key: 'admin_auth' }, // vẫn giữ key default cho Pinia
  },
)
