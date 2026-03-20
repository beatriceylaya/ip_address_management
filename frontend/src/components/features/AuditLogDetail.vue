<script setup lang="ts">
import { computed } from 'vue'
import type { AuditLog } from '@/types/audit-log'

const props = defineProps<{ log: AuditLog }>()

const causerName = props.log.causer?.name ?? 'System'
const subjectId  = props.log.subject_id

const authLogs = props.log.log_name === 'auth'
const ipAddressLogs = props.log.log_name === 'ip_address'

const isLogin = props.log.description === 'logged_in'
const isLogOut = props.log.description === 'logged_out'

const isCreated = props.log.event === 'created'
const isUpdated = props.log.event === 'updated'
const isDeleted = props.log.event === 'deleted'

const FIELD_LABELS: Record<string, string> = {
  ip_address: 'IP address',
  label:      'label',
  comment:    'comment',
}

const fieldLabel = (field: string) => FIELD_LABELS[field] ?? field.replace(/_/g, ' ')

const changes = computed(() => {
  const attrs = props.log.properties?.attributes ?? {}
  const old   = props.log.properties?.old ?? {}
  return Object.entries(attrs).map(([field, newVal]) => ({
    field,
    label:  fieldLabel(field),
    oldVal: old[field] ?? null,
    newVal,
  }))
})

const summaryAttrs = computed(() => {
  const source = isDeleted
    ? (props.log.properties?.old ?? {})
    : (props.log.properties?.attributes ?? {})

  return Object.entries(source)
    .filter(([k]) => ['ip_address', 'label'].includes(k))
    .map(([k, v]) => ({ label: fieldLabel(k), value: String(v) }))
})
</script>

<template>
  <div v-if="authLogs" class="flex-flex-col gap-1">
    <p class="text-sm">
      <span class="font-medium">{{ causerName }}</span>
      <span v-if="isLogin" class="text-green-600"> logged in.</span>
      <span v-else-if="isLogOut" class="text-green-600"> logged in.</span>
      <span v-else class="text-gray-500">{{ log.description }}</span>
    </p>
  </div>

  <div v-else-if="ipAddressLogs" class="flex flex-col gap-1 5">
    <!-- Created -->
    <template v-if="isCreated">
      <p class="text-sm">
        <span class="font-medium">{{ causerName }}</span>
          created IP address
        <span v-if="subjectId" class="font-mono text-xs bg-surface-100 px-1 py-0.5 rounded">#{{ subjectId }}</span>.
      </p>
      <div v-if="summaryAttrs.length" class="flex flex-wrap gap-2 mt-0.5">
        <span v-for="attr in summaryAttrs" :key="attr.label" class="text-xs text-gray-500">
          <span class="text-gray-400">{{ attr.label }}:</span>
          <span class="font-mono ml-1">{{ attr.value }}</span>
        </span>
      </div>
    </template>

    <!-- Updated -->
    <template v-else-if="isUpdated">
      <p class="text-sm mb-0.5">
        <span class="font-medium">{{ causerName }}</span>
        updated IP address
        <span v-if="subjectId" class="font-mono text-xs bg-surface-100 px-1 py-0.5 rounded">#{{ subjectId }}</span>:
      </p>
      <ul class="flex flex-col gap-0.5">
        <li v-for="change in changes" :key="change.field" class="text-xs flex flex-wrap items-center gap-1.5">
          <span class="text-gray-500 w-24 shrink-0">{{ change.label }}</span>
          <span v-if="change.oldVal !== null" class="font-mono bg-red-50 text-red-700 px-1.5 py-0.5 rounded line-through">{{ change.oldVal }}</span>
          <span v-if="change.oldVal !== null" class="text-gray-400">→</span>
          <span class="font-mono bg-green-50 text-green-700 px-1.5 py-0.5 rounded">{{ change.newVal }}</span>
        </li>
      </ul>
    </template>

    <!-- Deleted -->
    <template v-else-if="isDeleted">
      <p class="text-sm">
        <span class="font-medium">{{ causerName }}</span>
        deleted IP address
        <span v-if="subjectId" class="font-mono text-xs bg-surface-100 bg-surface-800 px-1 py-0.5 rounded">#{{ subjectId }}</span>.
      </p>
      <div v-if="summaryAttrs.length" class="flex flex-wrap gap-2 mt-0.5">
        <span v-for="attr in summaryAttrs" :key="attr.label" class="text-xs text-gray-400 line-through">
          <span>{{ attr.label }}:</span>
          <span class="font-mono ml-1">{{ attr.value }}</span>
        </span>
      </div>
    </template>

    <!-- Default -->
    <template v-else>
      <p class="text-sm text-gray-500">{{ log.description }}</p>
    </template>
  </div>

  <!-- Default -->
  <p v-else class="text-sm text-gray-500 italic">{{ log.description }}</p>
</template>