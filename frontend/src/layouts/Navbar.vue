<script setup lang="ts">
import { Button } from '@/components/common'

interface Props {
  userName?: string
}

defineProps<Props>()
</script>

<template>

  <header class="bg-white border-b px-6 py-4 flex items-center justify-between">

    <h1 class="text-lg font-semibold">
      Dashboard
    </h1>

    <div class="flex items-center gap-4">

      <span class="text-sm text-gray-600">
        {{ userName }}
      </span>

      <Button variant="secondary">
        Logout
      </Button>

    </div>
  </header>

</template>
    