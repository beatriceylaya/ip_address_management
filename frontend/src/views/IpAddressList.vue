<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useIpAddresses } from '@/composables/useIpAddresses'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/common/BaseDataTable.vue'
import Button from '@/components/common/BaseButton.vue'
import IpAddressModal from '@/components/features/IpAddressModal.vue'
import type { CreateIpAddressPayload, IpAddress, UpdateIpAddressPayload } from '@/types/ip-address'

const {
  ipAddresses,
  currentPage,
  totalPages,
  hasNextPage,
  fetchAll,
  create,
  update,
  remove
} = useIpAddresses()

const updateModalRef = ref<InstanceType<typeof IpAddressModal> | null>(null)
const selectedIpAddress = ref<IpAddress | null>(null)

const openUpdateModal = (item: IpAddress) => {
  selectedIpAddress.value = item
  updateModalRef.value?.open(item)
}

onMounted(() => {
  fetchAll()
})

const goToPage = (page: number) => {
  fetchAll(page)
}

const handleCreate = async (payload: CreateIpAddressPayload) => {
  await create(payload)
}

const handleUpdate = async (payload: CreateIpAddressPayload) => {
  if (!selectedIpAddress.value) return
  const updatePayload: UpdateIpAddressPayload = payload
  await update(selectedIpAddress.value.id, updatePayload)
  updateModalRef.value?.close()
}

const handleDelete = async(id: number) => {
  await remove(id)
}
</script>

<template>
  <AppLayout>
    <IpAddressModal @submit="handleCreate" class="mb-2"/>
    <IpAddressModal
      ref="updateModalRef"
      mode="update"
      :hideTrigger="true"
      @submit="handleUpdate"
    />
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
          <Button
            variant="secondary"
            class="mr-2"
            @click="openUpdateModal(item)"
          >
            Edit
          </Button>
          <Button variant="danger" @click="handleDelete">
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