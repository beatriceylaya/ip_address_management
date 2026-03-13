<script setup lang="ts"></script>
<template>
  <div class="bg-white border rounded-lg shadow-sm">
    <div v-if="$slots.header" class="px-6 py-4 border-b">
      <slot name="header" />
    </div>

    <div class="p-6">
      <slot />
    </div>

    <div v-if="$slots.footer" class="px-6 py-4 border-t">
      <slot name="footer" />
    </div>
  </div>
</template>