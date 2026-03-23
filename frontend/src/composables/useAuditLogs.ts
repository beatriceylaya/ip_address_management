import { reactive, ref } from 'vue'
import { auditLogService } from '@/services/auditLogService'
import type { AuditFilters, AuditLog, AuditTab, AuditScope, SessionOption } from '@/types/audit-log'

export function useAuditLogs() {
  const auditLogs = ref<AuditLog[]>([])
  const totalPages = ref(0)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const activeTab   = ref<AuditTab>('all')
  const activeScope = ref<AuditScope>('lifetime')

  const filters = reactive<AuditFilters>({
    page: 1,
    per_page: 15,
  })

  const sessionOptions  = ref<SessionOption[]>([])
  const sessionsLoading = ref(false)

  async function fetchAll() {
    loading.value = true
    error.value = null

    try {
      const { data } = await auditLogService.getAll(filters)

      auditLogs.value = data.data
      totalPages.value = data.meta.total
    } catch {
      error.value = 'Failed to fetch audit logs'
    } finally {
      loading.value = false
    }
  }

  async function loadSessionOptions(search = '') {
    sessionsLoading.value = true
    try {
      const { data } = await auditLogService.getSessionOptions({
        search,
        ip_address: filters.ip_address,
        causer_id:  filters.causer_id,
      })
      sessionOptions.value = data.data
    } finally {
      sessionsLoading.value = false
    }
  }

  function switchTab(tab: AuditTab) {
    activeTab.value      = tab
    activeScope.value    = 'lifetime'
    sessionOptions.value = []

    Object.assign(filters, {
      causer_id:  undefined,
      ip_address: undefined,
      session_id: undefined,
      event:      undefined,
      page:       1,
    })

    fetchAll()
  }

  function switchScope(scope: AuditScope) {
    activeScope.value    = scope
    filters.session_id   = undefined
    sessionOptions.value = []

    if (scope === 'by_session') {
      loadSessionOptions()
    }

    filters.page = 1
    fetchAll()
  }

  function setIpAddress(ip: string | undefined) {
    filters.ip_address = ip
    filters.session_id = undefined
    sessionOptions.value = []
    filters.page = 1

    if (activeScope.value === 'by_session') {
      loadSessionOptions()
    }

    fetchAll()
  }

  function setCauserId(userId: number | undefined) {
    filters.causer_id  = userId
    filters.session_id = undefined
    sessionOptions.value = []
    filters.page = 1

    if (activeScope.value === 'by_session') {
      loadSessionOptions()
    }

    fetchAll()
  }

  function setSessionId(sessionId: string | undefined) {
    filters.session_id = sessionId
    filters.page = 1
    fetchAll()
  }

  function applyFilters(partial: Partial<AuditFilters>) {
    Object.assign(filters, partial, { page: 1 })
    fetchAll()
  }

  function setPage(page: number) {
    filters.page = page
    fetchAll()
  }

  return {
    auditLogs,
    totalPages,
    loading,
    error,
    fetchAll,
    filters,
    applyFilters,
    setPage,
    sessionOptions,
    sessionsLoading,
    loadSessionOptions,
    activeTab,
    activeScope,
    switchScope,
    switchTab,
    setIpAddress,
    setCauserId,
    setSessionId,
  }
}