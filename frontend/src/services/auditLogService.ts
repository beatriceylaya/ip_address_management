import api from "@/api/axios";
import type { AuditFilters, AuditLog, SessionOption } from "@/types/audit-log";
import type { PaginatedResponse, } from "@/types/common";


export const auditLogService = {
  getAll: (filters: AuditFilters = {}) =>
    api.get<PaginatedResponse<AuditLog>>('audit-logs', { params: filters }),

  getSessionOptions: (params: {
    search?: string
    page?: number
    ip_address?: string
    causer_id?: number | string
  } = {}) => 
    api.get<PaginatedResponse<SessionOption>>('audit-logs/options/sessions', { params }),
}