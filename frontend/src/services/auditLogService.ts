import api from "@/api/axios";
import type { AuditLog } from "@/types/audit-log";
import type { PaginatedResponse } from "@/types/common";


export const auditLogService = {
  getAll: (page = 1, perPage = 15) =>
    api.get<PaginatedResponse<AuditLog>>('audit-logs', { params: { page, perPage } }),
}