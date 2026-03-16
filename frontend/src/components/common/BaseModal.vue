<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  modelValue: boolean
  title?: string
  width?: string
  persistent?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  width: 'max-w-lg',
  persistent: false
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const isOpen = computed({
  get: () => props.modelValue,
  set: (value: boolean) => emit('update:modelValue', value)
})

function close() {
  if (!props.persistent) {
    isOpen.value = false
  }
}

</script>

<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="close"></div>

      <div class="relative bg-white rounded-lg shadow-lg w-full mx-4" :class="width">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <slot name="header">
            <h2 class="text-lg font-semibold">
              {{ title }}
            </h2>
          </slot>

          <button class="text-gray-500 hover:text-gray-700" type="button" @click="close">
            Close
          </button>
        </div>

        <div class="px-6 py-4">
          <slot />
        </div>

        <div
          v-if="$slots.footer"
          class="px-6 py-4 border-t flex justify-end gap-2"
        >
          <slot name="footer" />
        </div>
      </div>
    </div>
  </Teleport>
</template>