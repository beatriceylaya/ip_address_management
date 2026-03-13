<script setup lang="ts">

interface Props {
  label?: string
  error?: string
}

defineProps<Props>()

</script>

<template>
    <div class="space-y-1">
  
      <label v-if="label" class="text-sm font-medium text-gray-700">
        {{ label }}
      </label>
  
      <slot />
  
      <p v-if="error" class="text-sm text-red-500">
        {{ error }}
      </p>
  
    </div>
  </template>