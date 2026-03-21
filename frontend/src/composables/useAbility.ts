import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function useAbility() {
  const auth = useAuthStore()

  return {
    canViewAnyIp: computed(() => auth.hasPermission('ip.view_any')),
    canCreateIp: computed(() => auth.hasPermission('ip.create')),
    canUpdateOwnIp: computed(() => auth.hasPermission('ip.update_own')),
    canUpdateAnyIp: computed(() => auth.hasPermission('ip.update_any')),
    canDeleteOwnIp: computed(() => auth.hasPermission('ip.delete_own')),
    canDeleteAnyIp: computed(() => auth.hasPermission('ip.delete_any')),

    canViewAuditLogs: computed(() => auth.hasPermission('audit_logs.view')),

    isSuperAdmin: auth.isSuperAdmin,
  }
}