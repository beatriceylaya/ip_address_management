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
  totalPages,
  loading,
  fetchAll,
  create,
  update,
  remove
} = useIpAddresses()

const tableColumns = [
  { field: 'ip_address', header: 'IP Address', sortable: true },
  { field: 'label', header: 'Label', sortable: true },
  { field: 'user', header: 'Created By' },
  { field: 'comment', header: 'Remarks' },
  { field: 'created_at', header: 'Date Added', sortable: true },
]

const updateModalRef = ref<InstanceType<typeof IpAddressModal> | null>(null)
const selectedIpAddress = ref<IpAddress | null>(null)

const openUpdateModal = (item: IpAddress) => {
  selectedIpAddress.value = item
  updateModalRef.value?.open(item)
}

onMounted(() => {
  loadIps()
})

const loadIps = async (page = 1, rows = 10) => {
  fetchAll(page)
}

const handlePagination = (event: any ) => {
  fetchAll(event.page + 1)
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

const confirmDelete = async(id: number) => {
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
      :columns="tableColumns"
      :total-records="totalPages"
      :loading="loading"
      @page-change="handlePagination"
    >
      <template #ip_address="{ data }">
        <span>{{ data.ip_address }}</span>
      </template>

      <template #user="{ data }">
        <span>{{ data.user?.name }}</span>
      </template>

      <template #actions="{ data }">
        <Button 
          icon="pi pi-pencil" 
          label="Edit"
          class="p-button-sm p-button-outlined p-button-warning" 
          @click="openUpdateModal(data)" 
        />
        <Button 
          icon="pi pi-trash" 
          label="Delete"
          class="p-button-sm p-button-outlined p-button-danger" 
          @click="confirmDelete(data.id)" 
        />
      </template>
    </DataTable>
  </AppLayout>
</template>