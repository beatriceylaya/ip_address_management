<script setup lang="ts">
import { onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/common/BaseDataTable.vue'
import { Select } from 'primevue'
import type { AuditScope, AuditTab } from '@/types/audit-log'
import { useAuditLogs } from '@/composables/useAuditLogs'
import dayjs from 'dayjs'
import AuditLogDetail from '@/components/features/AuditLogDetail.vue'
import { useIpAddresses } from '@/composables/useIpAddresses'
import { useUsers } from '@/composables/useUser'

const {
  auditLogs,
  totalPages,
  loading,
  error,
  fetchAll,
  filters,
  sessionOptions,
  sessionsLoading,
  loadSessionOptions,
  activeTab,
  activeScope,
  switchTab,
  switchScope,
  setIpAddress,
  setCauserId,
  setSessionId,
  setPage,
} = useAuditLogs()

const { fetchAll: fetchAllIpAddresses, ipAddresses } = useIpAddresses()
const { users, fetchAll: fetchUsers } = useUsers()

const TABS: { key: AuditTab; label: string }[] = [
  { key: 'all',     label: 'All Activity'  },
  { key: 'by_ip',   label: 'By IP Address' },
  { key: 'by_user', label: 'By User'       },
]

const SCOPE_OPTIONS: { label: string; value: AuditScope }[] = [
  { label: 'Lifetime',   value: 'lifetime'   },
  { label: 'By Session', value: 'by_session' },
]


const tableColumns = [
  { field: 'created_at',  header: 'Date & Time'  },
  { field: 'causer',      header: 'User'         },
  { field: 'event',       header: 'Event'        },
  { field: 'properties',  header: 'Details'      },
  { field: 'ip_address',  header: 'User Session - IP Address'  },
]

const formatDate = (iso: string) =>
  dayjs(new Date(iso)).format('YYYY-MM-DD HH:mm:ss')


const handlePagination = (event: { page: number }) => {
  setPage(event.page + 1)
}

onMounted(() => {
  fetchAll()
  fetchAllIpAddresses()
  fetchUsers()
})
</script>

<template>
<AppLayout>
  <div class="space-y-4 mb-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-xl font-semibold">Audit Log</h1>
        <p class="text-sm text-gray-500">Track all user activities, login events, and IP address changes</p>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 items-center">
      <button
        v-for="tab in TABS"
        :key="tab.key"
        :class="[
          'px-4 py-2 text-sm font-medium transition-colors',
          activeTab === tab.key
            ? 'bg-white border border-b-white border-gray-200 -mb-px text-gray-900'
            : 'text-gray-500 hover:text-gray-700',
        ]"
        @click="switchTab(tab.key)"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- Filter -->
    <div class="flex flex-wrap items-center gap-3">
      <!-- All Activities -->
      <template v-if="activeTab === 'all'">
      </template>

      <!-- By IP Address -->
      <template v-else-if="activeTab === 'by_ip'">
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
            IP Address:
          </label>
          <Select
            :model-value="filters.ip_address"
            :options="ipAddresses"
            option-label="ip_address"
            option-value="id"
            placeholder="All IP Addresses"
            filter
            filter-placeholder="Search IP..."
            show-clear
            class="w-48"
            @change="setIpAddress($event.value ?? undefined)"
          >
            <template #option="{ option }">
              <div>
                <p class="text-sm font-mono leading-tight">{{ option.ip_address }}</p>
                <p class="text-xs text-gray-400 leading-tight">by {{ option.user.name }}</p>
              </div>
            </template>
          </Select>
        </div>


        <!-- Scope -->
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Scope:</label>
            <Select
              :model-value="activeScope"
              :options="SCOPE_OPTIONS"
              option-label="label"
              option-value="value"
              class="w-36"
              @change="switchScope($event.value)"
            />
          </div>

        <!-- Session -->
        <Transition name="slide-fade">
            <div v-if="activeScope === 'by_session'" class="flex items-center gap-2">
              <label class="text-sm font-medium text-gray-700">Session:</label>
              <Select
                :model-value="filters.session_id"
                :options="sessionOptions"
                option-label="session_id"
                option-value="session_id"
                placeholder="All Sessions"
                filter
                filter-placeholder="Search session..."
                show-clear
                :loading="sessionsLoading"
                empty-message="No sessions found"
                class="w-52"
                @filter="loadSessionOptions($event.value)"
                @change="setSessionId($event.value ?? undefined)"
              >
                <!-- Option row -->
                <template #option="{ option }">
                  <span class="font-mono text-xs">
                    {{ option.session_id.slice(0, 24) }}…
                  </span>
                </template>

                <!-- Selected value -->
                <template #value="{ value }">
                  <span v-if="value" class="font-mono text-xs">
                    {{ value.slice(0, 24) }}…
                  </span>
                  <span v-else class="text-gray-400">All Sessions</span>
                </template>

                <template #header>
                  <div
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-50"
                    :class="{ 'font-medium': !filters.session_id }"
                    @click="setSessionId(undefined)"
                  >
                    All Sessions
                  </div>
                </template>
              </Select>
            </div>
          </Transition>
        </template>

        <!-- By User -->
        <template v-else-if="activeTab === 'by_user'">
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">User:</label>
            <Select
              :model-value="filters.causer_id"
              :options="users"
              option-label="email"
              option-value="id"
              placeholder="All Users"
              filter
              filter-placeholder="Search user..."
              show-clear
              class="w-56"
              @change="setCauserId($event.value ?? undefined)"
            >
              <template #option="{ option }">
                <div>
                  <p class="text-sm font-medium leading-tight">{{ option.name }}</p>
                  <p class="text-xs text-gray-400 leading-tight">{{ option.email }}</p>
                </div>
              </template>
              <template #value="{ value }">
                <span v-if="value">{{ users.find(u => u.id === filters.causer_id)?.name }}</span>
                <span v-else class="text-gray-400">All Users</span>
              </template>
            </Select>
          </div>

          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Scope:</label>
            <Select
              :model-value="activeScope"
              :options="SCOPE_OPTIONS"
              option-label="label"
              option-value="value"
              class="w-36"
              @change="switchScope($event.value)"
            />
          </div>

          <Transition name="slide-fade">
            <div v-if="activeScope === 'by_session'" class="flex items-center gap-2">
              <label class="text-sm font-medium text-gray-700">Session:</label>
              <Select
                :model-value="filters.session_id"
                :options="sessionOptions"
                option-label="session_id"
                option-value="session_id"
                placeholder="All Sessions"
                filter
                filter-placeholder="Search session..."
                show-clear
                :loading="sessionsLoading"
                empty-message="No sessions found"
                class="w-52"
                @filter="loadSessionOptions($event.value)"
                @change="setSessionId($event.value ?? undefined)"
              >
                <template #option="{ option }">
                  <span class="font-mono text-xs">{{ option.session_id.slice(0, 24) }}…</span>
                </template>
                <template #value="{ value }">
                  <span v-if="value" class="font-mono text-xs">{{ value.slice(0, 24) }}…</span>
                  <span v-else class="text-gray-400">All Sessions</span>
                </template>
                <template #header>
                  <div
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-50"
                    :class="{ 'font-medium': !filters.session_id }"
                    @click="setSessionId(undefined)"
                  >
                    All Sessions
                  </div>
                </template>
              </Select>
            </div>
          </Transition>
        </template>

    </div>
  
    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
  </div>

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

    <template #properties="{ data }">
      <AuditLogDetail :log="data" />
    </template>

    <template #actions>
      <span />
    </template>

  </DataTable>
</AppLayout>
</template>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-6px);
}
</style>