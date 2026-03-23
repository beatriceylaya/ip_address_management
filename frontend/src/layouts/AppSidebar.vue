<script setup lang="ts">
import { useAbility } from '@/composables/useAbility'

const { isSuperAdmin } = useAbility()
</script>
<template>
  <aside class="w-64 bg-gray-900 text-white min-h-screen">

    <div class="p-4 text-lg font-semibold border-b border-gray-700">
      IP Manager
    </div>

    <nav class="p-4 space-y-2">

      <RouterLink
        to="/ip-addresses"
        active-class="text-blue-500 font-bold"
        class="block px-3 py-2 rounded hover:bg-gray-800"
      >
        IP Address
      </RouterLink>

      <RouterLink
        v-if="isSuperAdmin"
        to="/audit-logs"
        active-class="text-blue-500 font-bold"
        class="block px-3 py-2 rounded hover:bg-gray-800"
      >
        Audit Logs
      </RouterLink>

    </nav>

  </aside>
</template>