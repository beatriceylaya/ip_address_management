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
  event: string | null
  created_at: string
  updated_at: string
}