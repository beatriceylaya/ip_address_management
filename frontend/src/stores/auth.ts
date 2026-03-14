import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import type { AuthUser } from '@/types/auth'
import { authService } from '@/services/authService'

export const useAuthStore = defineStore('auth', () => {
  const accessToken = ref<string | null>(null)
  const refreshToken = ref<string | null>(null)
  const user = ref<AuthUser | null>(null)

  const isAuthenticated = computed(() => !!accessToken.value)

  function setTokens(access: string, refresh: string) {
    accessToken.value = access
    refreshToken.value = refresh ?? null
  }

  function setUser(u: AuthUser) {
    user.value = u
  }

  async function refresh(): Promise<string> {
    if (!refreshToken.value) throw new Error('No refresh token')
    const { data } = await authService.refresh(refreshToken.value)
    accessToken.value = data.access_token
    return data.access_token
  }

  function logout() {
    accessToken.value = null
    refreshToken.value = null
    user.value = null
  }

  return {
    accessToken, refreshToken, user,
    isAuthenticated,
    setTokens, setUser, refresh, logout
  }
}, {
  persist: {
    paths: ['accessToken', 'refreshToken'],
  },
} as any)