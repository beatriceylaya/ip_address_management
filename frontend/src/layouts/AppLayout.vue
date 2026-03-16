<template>
  <div class="min-h-screen bg-gray-50 flex">

    <Sidebar />
    <div class="flex-1 flex flex-col">
      <Navbar :name="user?.name ?? ''" />
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import Sidebar from './AppSidebar.vue'
import Navbar from './AppNavbar.vue'
import { useAuth } from '@/composables/useAuth'

const { user } = useAuth()
</script>