<script setup lang="ts"></script>

<template>
  <div class="overflow-x-auto">

    <table class="min-w-full divide-y divide-gray-200">

      <thead class="bg-gray-50">
        <tr>
          <slot name="head" />
        </tr>
      </thead>

      <tbody class="bg-white divide-y">
        <slot />
      </tbody>

    </table>

  </div>
</template>