<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useIpAddresses } from '@/composables/useIpAddresses'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/common/BaseDataTable.vue'
import Button from 'primevue/button'
import IpAddressModal from '@/components/features/IpAddressModal.vue'
import type { CreateIpAddressPayload, IpAddress } from '@/types/ip-address'
import dayjs from 'dayjs'
import { useConfirm } from 'primevue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useAbility } from '@/composables/useAbility'
import { useAuthStore } from '@/stores/auth'

const { canUpdateAnyIp, canDeleteAnyIp } = useAbility()
const auth = useAuthStore()

const canEditIp = (ownerId: number) =>
  canUpdateAnyIp.value ||
  (auth.hasPermission('ip.update_own') && ownerId === auth.user?.id)


const canDeleteIp = (ownerId: number) =>
  canDeleteAnyIp.value ||
  (auth.hasPermission('ip.delete_own') && ownerId === auth.user?.id)

const confirm = useConfirm()
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
  { field: 'comment', header: 'Comment' },
  { field: 'user', header: 'Created By' },
  { field: 'created_at', header: 'Date Added', sortable: true },
]

const updateModalRef = ref<InstanceType<typeof IpAddressModal> | null>(null)
const selectedIpAddress = ref<IpAddress | null>(null)

const openUpdateModal = (item: IpAddress) => {
  selectedIpAddress.value = item
  updateModalRef.value?.open(item)
}

const handlePagination = (event: { page: number } ) => {
  fetchAll(event.page + 1)
}

const handleCreate = async (
  payload: CreateIpAddressPayload,
  onSuccess: () => void,
  onError: (err: unknown) => void
) => {
  try {
    await create(payload)
    onSuccess()
  } catch (err) {
    onError(err)
  }
}

const handleUpdate = async (
  payload: CreateIpAddressPayload,
  onSuccess: () => void,
  onError: (err: unknown) => void
) => {
  if (!selectedIpAddress.value) return
  try {
    await update(selectedIpAddress.value.id, payload)
    onSuccess()
  } catch (err) {
    onError(err)
  }
}

const confirmDelete = (id: number) => {
  confirm.require({
    message: 'Are you sure you want to delete this entry?',
    header: 'Delete Confirmation',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: '!bg-red-600 !border-red-600 !text-white hover:!bg-red-700',
    rejectClass: '!bg-gray-200 !text-gray-700 !border-gray-300 hover:!bg-gray-300',
    accept: async () => {
      await remove(id)
    },
  })
}

const formatDate = (iso: string) =>
  dayjs(new Date(iso)).format('YYYY-MM-DD')

onMounted(() => {
  fetchAll()
})
</script>

<template>
  <AppLayout>
    <div class="space-y-4 mb-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold">IP Addresses</h1>
          <p class="text-sm text-gray-500">Manage and track all registered IP addresses</p>
        </div>

        <IpAddressModal @submit="handleCreate" class="mb-2"/>
      </div>
    </div>
    
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

      <template #created_at="{ data }">
        <span>{{ formatDate(data.created_at) }}</span>
      </template>

      <template #actions="{ data }">
        <Button 
          icon="pi pi-pencil" 
          label="Edit"
          class="p-button-sm p-button-outlined p-button-warning"
          :disabled="!canEditIp(data.user_id)"
          @click="openUpdateModal(data)" 
        />
        <Button 
          icon="pi pi-trash" 
          label="Delete"
          class="p-button-sm p-button-outlined p-button-danger"
          :disabled="!canDeleteIp(data.user_id)"
          @click="confirmDelete(data.id)"
        />
      </template>
    </DataTable>

    <ConfirmDialog />
  </AppLayout>
</template>