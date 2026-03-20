<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import BaseButton from '../common/BaseButton.vue'
import BaseModal from '../common/BaseModal.vue'
import FormGroup from '../form/FormGroup.vue'
import BaseInput from '../common/BaseInput.vue'
import type { IpAddress, CreateIpAddressPayload } from '@/types/ip-address'

type ModalMode = 'create' | 'update'

type IpAddressForm = CreateIpAddressPayload

const form = reactive<IpAddressForm>({
  ip_address: '',
  label: '',
  comment: ''
})

const fieldErrors = reactive<FieldErrors>({})
const generalError = ref<string | null>(null)

interface FieldErrors {
  ip_address?: string
  label?: string
  comment?: string
}

interface Props {
  mode?: ModalMode
  hideTrigger?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  mode: 'create',
  hideTrigger: false
})

const emit = defineEmits<{
  (
    e: 'submit',
    payload: CreateIpAddressPayload,
    onSuccess: () => void,
    onError: (err: unknown) => void
  ): void
}>()

const showModal = ref(false)
const isBusy = ref(false)

const title = computed(() =>
  props.mode === 'update' ? 'Update IP Address' : 'Create IP Address'
)

const primaryLabel = computed(() =>
  props.mode === 'update' ? 'Save changes' : 'Save'
)

function resetForm() {
  form.ip_address = ''
  form.label = ''
  form.comment = ''
}

function clearErrors() {
  delete fieldErrors.ip_address
  delete fieldErrors.label
  delete fieldErrors.comment
  generalError.value = null
}

function applyErrors(err: unknown) {
  clearErrors()

  const data =
    err &&
    typeof err === 'object' &&
    'response' in err &&
    err.response &&
    typeof err.response === 'object' &&
    'data' in err.response
      ? (err.response as { data: unknown }).data
      : err

  if (!data || typeof data !== 'object') {
    generalError.value = 'An unexpected error occurred. Please try again.'
    return
  }

  const e = data as { errors?: Record<string, string[]>; message?: string }
  const formKeys = Object.keys(form) as (keyof FieldErrors)[]

  if (e.errors && typeof e.errors === 'object') {
    const unknownMessages: string[] = []

    for (const [key, messages] of Object.entries(e.errors)) {
      const firstMessage = messages[0]
      if (formKeys.includes(key as keyof FieldErrors)) {
        if (firstMessage !== undefined) fieldErrors[key as keyof FieldErrors] = firstMessage
      } else {
        if (firstMessage !== undefined) unknownMessages.push(firstMessage)
      }
    }

    if (unknownMessages.length) {
      generalError.value = unknownMessages.join(' ')
    }

    return
  }

  generalError.value = e.message ?? 'An unexpected error occurred. Please try again.'
}

function applyIpAddressToForm(item: IpAddress) {
  form.ip_address = item.ip_address ?? ''
  form.label = item.label ?? ''
  form.comment = item.comment ?? ''
}

// Modal
function open(item?: IpAddress) {
  clearErrors()
  if (props.mode === 'update' && item) {
    applyIpAddressToForm(item)
  } else if (props.mode === 'create') {
    resetForm()
  }
  showModal.value = true
}

function close () {
  if (isBusy.value) return
  clearErrors()
  showModal.value = false
}

defineExpose({
  open,
  close
})

function submit() {
  clearErrors()
  isBusy.value = true

  emit(
    'submit',
    {
      ip_address: form.ip_address,
      label: form.label,
      comment: form.comment,
    },
    () => {
      isBusy.value = false
      close()
    },
    (err: unknown) => {
      isBusy.value = false
      applyErrors(err)
    }
  )
}
</script>

<template>
  <div>
    <BaseButton
      v-if="!hideTrigger"
      @click="open"
    >
      {{ mode === 'update' ? 'Update IP Address' : 'Create IP Address' }}
    </BaseButton>

    <BaseModal
      v-model="showModal"
      :title="title"
    >
      <template #default>
        <form id="ip-address-form" class="space-y-4" @submit.prevent="submit">
          <FormGroup label="IP Address" :error="fieldErrors.ip_address">
            <BaseInput v-model="form.ip_address" placeholder="e.g. 10.0.0.15" required />
          </FormGroup>

          <FormGroup label="Label" :error="fieldErrors.label">
            <BaseInput v-model="form.label" placeholder="e.g. VPN gateway" />
          </FormGroup>

          <FormGroup label="Comment" :error="fieldErrors.comment">
            <textarea
              v-model="form.comment"
              rows="3"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Optional notes..."
            />
          </FormGroup>

          <p
            v-if="generalError"
            class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-md px-3 py-2"
          >
            {{ generalError }}
          </p>
        </form>
      </template>

      <template #footer>
        <BaseButton variant="secondary" @click="close" :disabled="isBusy">
          Cancel
        </BaseButton>

        <BaseButton type="submit" form="ip-address-form" :disabled="isBusy" :loading="isBusy">
          {{ isBusy ? 'Saving... ' : primaryLabel }}
        </BaseButton>
      </template>
    </BaseModal>
  </div>
</template>