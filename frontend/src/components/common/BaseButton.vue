<script setup lang="ts">
import { computed } from 'vue'

type ButtonVariant = 'primary' | 'secondary' | 'danger'

interface Props {
    variant?: ButtonVariant
    type?: 'button' | 'submit' | 'reset'
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    type: 'button'
})

const variantClasses = computed(() => {
  const variants: Record<ButtonVariant, string> = {
    primary: 'bg-blue-600 text-white hover:bg-blue-700',
    secondary: 'bg-gray-100 hover:bg-gray-200',
    danger: 'bg-red-600 text-white hover:bg-red-700'
  }
  return variants[props.variant]
})
</script>

<template>
  <button
    :type="type"
    :class="[
      'px-4 py-2 text-sm font-medium rounded-md transition',
      variantClasses
    ]"
  >
    <slot />
  </button>
</template>
