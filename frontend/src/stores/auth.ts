import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import type { AuthUser, RoleName } from '@/types/auth'
import { authService } from '@/services/authService'

export const useAuthStore = defineStore('auth', () => {
  const accessToken = ref<string | null>(null)
  const refreshToken = ref<string | null>(null)
  const user = ref<AuthUser | null>(null)

  const isAuthenticated = computed(() => !!accessToken.value)

  // Roles and Permissions
  const roles = computed(() => user.value?.roles?.map(r => r.name) ?? [])
  const permissions = computed(() =>
    user.value?.roles?.flatMap(r => r.permissions?.map(p => p.name) ?? []) ?? []
  )
  const isSuperAdmin = computed(() => roles.value.includes('super_admin'))
  const isUser = computed(() => roles.value.includes('user'))

  function hasPermission(permission: string): boolean {
    return permissions.value.includes(permission)
  }

  function hasRole(role: RoleName): boolean {
    return roles.value.includes(role)
  }

  function hasAnyPermission(perms: string[]): boolean {
    return perms.some(p => permissions.value.includes(p))
  }

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
    accessToken,
    refreshToken,
    user,
    roles,
    permissions,
    isSuperAdmin,
    isUser,
    hasPermission,
    hasRole,
    hasAnyPermission,
    isAuthenticated,
    setTokens,
    setUser,
    refresh,
    logout,
    clearAuth: logout,
  }
}, {
  persist: {
    pick: ['accessToken', 'refreshToken', 'user'],
  },
})