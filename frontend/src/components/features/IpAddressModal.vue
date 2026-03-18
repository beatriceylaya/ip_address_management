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

interface Props {
  mode?: ModalMode
  hideTrigger?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  mode: 'create',
  hideTrigger: false
})

const emit = defineEmits<{
  (e: 'submit', payload: CreateIpAddressPayload): void
}>()

const showModal = ref(false)

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

function applyIpAddressToForm(item: IpAddress) {
  form.ip_address = item.ip_address ?? ''
  form.label = item.label ?? ''
  form.comment = item.comment ?? ''
}

function open(item?: IpAddress) {
  if (props.mode === 'update' && item) {
    applyIpAddressToForm(item)
  } else if (props.mode === 'create') {
    resetForm()
  }
  showModal.value = true
}

function close () {
  showModal.value = false
}

defineExpose({
  open,
  close
})

function submit() {
  emit('submit', {
    ip_address: form.ip_address,
    label: form.label,
    comment: form.comment
  })
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
          <FormGroup label="IP Address">
            <BaseInput v-model="form.ip_address" placeholder="e.g. 10.0.0.15" required />
          </FormGroup>

          <FormGroup label="Label">
            <BaseInput v-model="form.label" placeholder="e.g. VPN gateway" />
          </FormGroup>

          <FormGroup label="Comment">
            <textarea
              v-model="form.comment"
              rows="3"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Optional notes..."
            />
          </FormGroup>
        </form>
      </template>

      <template #footer>
        <BaseButton variant="secondary" @click="close">
          Cancel
        </BaseButton>

        <BaseButton type="submit" form="ip-address-form">
          {{ primaryLabel }}
        </BaseButton>
      </template>
    </BaseModal>
  </div>
</template>