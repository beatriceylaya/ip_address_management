<script setup lang="ts">
import { RouterView } from 'vue-router'
import { onMounted  } from 'vue'
import { useAuthStore } from './stores/auth'
import { authService } from './services/authService'

const authStore = useAuthStore()

onMounted(async (): Promise<void> => {
  if (authStore.isAuthenticated) {
    try {
      const { data: user } = await authService.profile()
      authStore.setUser(user)
    } catch {
      //
    }
  }
})
</script>

<template>
  <RouterView />
</template>
