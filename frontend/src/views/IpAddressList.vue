<script setup lang="ts">
import { onMounted } from 'vue'
import { useIpAddresses } from '@/composables/useIpAddresses'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/common/DataTable.vue'
import Button from '@/components/common/Button.vue'

const {
  ipAddresses,
  currentPage,
  totalPages,
  hasNextPage,
  fetchAll 
} = useIpAddresses()

onMounted(() => {
  fetchAll()
})

const goToPage = (page: number) => {
  fetchAll(page)
}
</script>

<template>
  <AppLayout>
    <DataTable
      title="Ip Addresses"
      :items="ipAddresses"
    >
      <template #head>
        <th class="px-6 py-3 text-left">IP Address</th>
        <th class="px-6 py-3 text-left">Created By</th>
        <th class="px-6 py-3 text-left">Created at</th>
        <th class="px-6 py-3 text-left">Actions</th>
      </template>

      <template #row="{ item }">
        <td class="px-6 py-4">
          {{ item.ip_address }}
        </td>

        <td class="px-6 py-4">
          {{ item.user?.name ?? item.created_by }}
        </td>

        <td class="px-6 py-4">
          {{ item.created_at }}
        </td>

        <td class="px-6 py-4">
          <Button variant="secondary" class="mr-2">
            Edit
          </Button>
          <Button variant="danger">
            Delete
          </Button>
        </td>
      </template>
    </DataTable>

    <div class="flex gap-4 mt-4">
      <button
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
      >
        Prev
      </button>

      <span>
        Page {{ currentPage }} of {{ totalPages }}
      </span>
      <button
        :disabled="currentPage === totalPages"
        @click="goToPage(currentPage + 1)"
      >
        Next
      </button>
    </div>
  </AppLayout>
</template>