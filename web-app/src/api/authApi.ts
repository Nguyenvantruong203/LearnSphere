import { http } from '@/helpers/http'
import type {
  RegisterPayload,
  RegisterResponse,
  LoginPayload,
  LoginResponse,
} from '@/types/user'

export const authApi = {
  async register(payload: RegisterPayload): Promise<RegisterResponse> {
    return await http('/api/register', {
      method: 'POST',
      body: payload,
    })
  },

  async login(payload: LoginPayload): Promise<LoginResponse> {
    return await http('/api/login', {
      method: 'POST',
      body: payload,
    })
  },

  async logout(): Promise<void> {
    return await http('/api/logout', {
      method: 'POST',
    })
  },

  async redirectToGoogle(): Promise<{ url: string }> {
    return await http('/api/auth/google/redirect', {
      method: 'GET',
    })
  },

  async handleGoogleCallback(code: string): Promise<LoginResponse> {
    return await http(`/api/google/callback-login?code=${code}`, {
      method: 'GET',
    })
  },
}
