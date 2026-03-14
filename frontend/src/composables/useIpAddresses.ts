import { ref, computed } from 'vue'
import { ipAddressService } from '@/services/ipAddressService'
import type { IpAddress, CreateIpAddressPayload } from '@/types/ip-address'

export function useIpAddresses() {
  const ipAddresses = ref<IpAddress[]>([])
  const currentIpAddress = ref<IpAddress | null>(null)
  const loading = ref(false)
  const saving = ref(false)
  const error = ref<string | null>(null)
  const currentPage = ref(1)
  const totalPages = ref(1)

  const hasNextPage = computed(() => currentPage.value < totalPages.value)

  async function fetchAll(page = 1) {
    loading.value = true
    error.value = null

    try {
      const { data } = await ipAddressService.getAll(page)
      ipAddresses.value = data.data
      currentPage.value = data.meta.current_page
      totalPages.value = data.meta.last_page
    } catch {
      error.value = 'Failed to fetch ip addresses'
    } finally {
      loading.value = false
    }
  }

  async function fetchOne(id: number) {
    loading.value = true
    try {
      const { data } = await ipAddressService.getById(id)
      currentIpAddress.value = data.data
    } catch {
      error.value = 'Post not found'
    } finally {
      loading.value = false
    }
  }

  async function create(payload: CreateIpAddressPayload) {
    saving.value = true
    error.value = null
    try {
      await ipAddressService.create(payload)
      await fetchAll()
    } catch (e) {
      error.value = 'Failed to create IP address'
      throw e
    } finally {
      saving.value = false
    }
  }

  async function update(id: number, payload: Partial<CreateIpAddressPayload>) {
    saving.value = true
    error.value = null
    try {
      await ipAddressService.update(id, payload)
      await fetchAll()
    } catch (e) {
      error.value = 'Failed to update IP address'
      throw e
    } finally {
      saving.value = false
    }
  }

  async function remove(id: number) {
    saving.value = true
    error.value = null
    try {
      await ipAddressService.delete(id)
      await fetchAll()
    } catch (e) {
      error.value = 'Failed to delete IP address'
      throw e
    } finally {
      saving.value = false
    }
  }

  return {
    ipAddresses, currentIpAddress, loading, error,
    currentPage, totalPages, hasNextPage,
    fetchAll, fetchOne, create, update, remove
  }
}