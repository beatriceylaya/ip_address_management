import api from '@/api/axios'
import type { AuthUser } from '@/types/auth'
import type { PaginatedResponse } from '@/types/common'

export const userService = {
  getAll: (params: { search?: string; page?: number } = {}) =>
    api.get<PaginatedResponse<AuthUser>>('users', { params }),
}