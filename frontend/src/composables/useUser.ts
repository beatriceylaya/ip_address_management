import { ref } from 'vue'
import { userService } from '@/services/userService'
import type { AuthUser } from '@/types/auth'

export function useUsers() {
  const users   = ref<AuthUser[]>([])
  const loading = ref(false)
  const error   = ref<string | null>(null)

  async function fetchAll() {
    loading.value = true
    error.value   = null
    try {
      const { data } = await userService.getAll()
      users.value = data.data
    } catch {
      error.value = 'Failed to fetch users.'
    } finally {
      loading.value = false
    }
  }

  return { users, loading, error, fetchAll }
}