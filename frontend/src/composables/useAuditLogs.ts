import { ref } from 'vue'
import { auditLogService } from '@/services/auditLogService'
import type { PaginatedResponse } from '@/types/common'
import type { AuditLog } from '@/types/audit-log'

export function useAuditLogs() {
  const auditLogs = ref<AuditLog[]>([])
  const totalPages = ref(0)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function fetchAll(page = 1) {
    loading.value = true
    error.value = null

    try {
      const { data } = await auditLogService.getAll(page)

      auditLogs.value = data.data
      totalPages.value = data.meta.total
    } catch {
      error.value = 'Failed to fetch audit logs'
    } finally {
      loading.value = false
    }
  }

  return {
    auditLogs,
    totalPages,
    loading,
    error,
    fetchAll,
  }
}