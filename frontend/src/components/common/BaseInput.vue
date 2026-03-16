<script setup lang="ts">

interface Props {
  modelValue?: string | number
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()

function updateValue(event: Event) {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', target.value)
}

</script>

<template>
  <input
    :value="modelValue"
    @input="updateValue"
    v-bind="$attrs"
    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
  />
</template>
