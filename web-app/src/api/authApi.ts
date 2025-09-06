import type { User } from '@/types/user'
import { http } from './http'

export type RegisterPayload = {
  username: string
  email: string
  password: string
  password_confirmation: string
}

export type LoginPayload = {
  email: string
  password: string
}

export type LoginResponse = {
  user: User
  access_token: string
  token_type: string
  message?: string
}

export type RegisterResponse = {
  message: string
}

export const authApi = {
  async register(payload: RegisterPayload): Promise<RegisterResponse> {
    return await http('/api/register', {
      method: 'POST',
      body: payload
    })
  },

  /**
   * Log in a user.
   */
  async login(payload: LoginPayload): Promise<LoginResponse> {
    return await http('/api/login', {
      method: 'POST',
      body: payload
    })
  },

  async logout(): Promise<void> {
    return await http('/api/logout', {
      method: 'POST'
    })
  },

  /**
   * Get the redirect URL for Google login.
   */
  async redirectToGoogle(): Promise<{ url: string }> {
    return await http('/api/auth/google/redirect', {
      method: 'GET'
    })
  },

  async handleGoogleCallback(code: string): Promise<LoginResponse> {
    return await http(`/api/auth/google/callback?code=${code}`, {
      method: 'GET'
    })
  }
}
