<script setup lang="ts">
import { Button } from '@/components/common'
import { useAuth } from '@/composables/useAuth';
interface Props {
  name?: string
}

defineProps<Props>()

const { logout } = useAuth()

async function submit() {
  await logout()
}
</script>

<template>

  <header class="bg-white border-b px-6 py-4 flex items-center">

    <div class="flex items-center gap-4 ml-auto">

      <span class="text-sm text-gray-600">
        {{ name }}
      </span>

      <Button variant="secondary" @click.prevent="submit">
        Logout
      </Button>

    </div>
  </header>

</template>
    