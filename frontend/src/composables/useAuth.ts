import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { authService } from '@/services/authService'
import type { LoginPayload, RegisterPayload } from '@/types/auth'
import router from '@/router'

export function useAuth() {
  const store = useAuthStore()
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function login(payload: LoginPayload) {
    loading.value = true
    error.value = null

    try {
      const { data } = await authService.login(payload)
      console.log(data)
      store.setTokens(data.access_token, data.refresh_token)
      store.setUser(data.user)
      await router.push('/')
    } catch (error: any) {
      error.value = error.response?.data?.message ?? 'Login failed'
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await authService.logout()
    } finally {
      store.logout()
      router.push('/login')
    }
  }

  async function register(payload: RegisterPayload) {
    loading.value = true
    error.value = null
    try {
      const { data } = await authService.register(payload)
      store.setTokens(data.access_token, data.refresh_token)
      store.setUser(data.user)
      await router.push('/')
    } catch (error: any) {
      error.value = error.response?.data?.message ?? 'Login failed'
    } finally {
      loading.value = false
    }
  }

  return {
    login,
    logout,
    register,
    loading,
    error,
    user: store.user,
    isAuthenticated: store.isAuthenticated,
  }
}