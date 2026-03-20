<script setup lang="ts">
import { onMounted, ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/common/BaseDataTable.vue'
import Button from '@/components/common/BaseButton.vue'
import type { AuditLog } from '@/types/audit-log'
import { useAuditLogs } from '@/composables/useAuditLogs'
import dayjs from 'dayjs'
import AuditLogDetail from '@/components/features/AuditLogDetail.vue'

const {
  auditLogs,
  totalPages,
  loading,
  error,
  fetchAll,
} = useAuditLogs()

const tableColumns = [
  { field: 'created_at',  header: 'Date & Time'  },
  { field: 'causer',      header: 'User'         },
  { field: 'log_name',    header: 'Log'          },
  { field: 'event',       header: 'Event'        },
  { field: 'description', header: 'Description'  },
  { field: 'properties',  header: 'Details'      },
]

onMounted(() => {
  fetchAll()
})

const formatDate = (iso: string) =>
  dayjs(new Date(iso)).format('YYYY-MM-DD')


const handlePagination = (event: { page: number }) => {
  fetchAll(event.page + 1)
}
</script>

<template>
<AppLayout>
  <DataTable
    :items="auditLogs"
    :columns="tableColumns"
    :total-records="totalPages"
    :loading="loading"
    :show-actions="false"
    @page-change="handlePagination"
  >
    <template #created_at="{ data }">
      <span class="whitespace-nowrap text-sm">{{ formatDate(data.created_at) }}</span>
    </template>

    <template #causer="{ data }">
      <div v-if="data.causer">
        <p class="font-medium text-sm leading-tight">{{ data.causer.name }}</p>
        <p class="text-xs text-gray-400 leading-tight">{{ data.causer.email }}</p>
      </div>
      <span v-else class="text-gray-400 italic text-sm">System</span>
    </template>

    <template #log_name="{ data }">
      <Tag
        :value="data.log_name ?? 'default'"
        class="uppercase text-xs"
      />
    </template>

    <template #description="{ data }">
      <span class="text-sm">{{ data.description }}</span>
    </template>

    <template #properties="{ data }">
      <AuditLogDetail :log="data" />
    </template>

    <template #actions>
      <span />
    </template>

  </DataTable>
</AppLayout>
</template>