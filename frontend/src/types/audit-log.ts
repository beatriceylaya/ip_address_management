export interface AuditLogCauser {
  id: number
  name: string
  email: string
}

export interface AuditLogProperties {
  old?: Record<string, unknown>
  attributes?: Record<string, unknown>
}

export interface AuditLog {
  id: number
  log_name: string | null
  description: string
  subject_type: string | null
  subject_id: number | null
  causer_type: string | null
  causer_id: number | null
  causer: AuditLogCauser | null
  properties: AuditLogProperties
  session_id: string | null
  ip_address: string | null
event: string | null
  created_at: string
  updated_at: string
}

export type AuditTab   = 'all' | 'by_ip' | 'by_user'
export type AuditScope = 'lifetime' | 'by_session'

export interface AuditFilters {
  causer_id?: number | string
  ip_address?: string
  session_id?: string
  event?: string
  date_from?: string
  date_to?: string
  page?: number
  per_page?: number
}

export interface SessionOption {
  session_id: string
}