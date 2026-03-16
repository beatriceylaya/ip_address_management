<script setup lang="ts">
import { ref } from 'vue'
import BaseButton from '../common/BaseButton.vue'
import BaseModal from '../common/BaseModal.vue'

const showModal = ref(false)

function close () {
  showModal.value = false
}
</script>

<template>
  <BaseButton @click="showModal = true">
    Create IP Address
  </BaseButton>

  <BaseModal
    v-model="showModal"
    title="Create IP Address"
  >
    <template #default>
      <p>Modal</p>
    </template>

    <template #footer>
      <BaseButton variant="secondary" @click="close">
        Cancel
      </BaseButton>

      <BaseButton>
        Save
      </BaseButton>
    </template>
  </BaseModal>
</template>