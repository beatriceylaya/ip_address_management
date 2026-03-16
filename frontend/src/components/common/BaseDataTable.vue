<script setup lang="ts" generic="T extends { id: number }">
import Card from '@/components/common/BaseCard.vue'
import Table from '@/components/common/BaseTable.vue'

interface Props {
  title: string
  items: T[]
}

defineProps<Props>()

</script>

<template>
  <Card>
  
    <template #header>
      <h2 class="font-semibold">{{ title }}</h2>
    </template>
  
    <Table>
  
      <template #head>
        <slot name="head" />
      </template>
  
      <tr v-for="item in items" :key="item.id">
        <slot name="row" :item="item"></slot>
      </tr>
  
    </Table>
  
  </Card>
  
</template>
