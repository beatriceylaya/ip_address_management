<script setup lang="ts" generic="T extends { id: number | string }">
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'

withDefaults(defineProps<{
  items: T[]
  columns: { field: string; header: string }[]
  totalRecords: number
  loading: boolean
  showActions?: boolean
}>(), {
  showActions: true,
})

defineEmits(['page-change', 'edit', 'delete'])
</script>

<template>
  <DataTable 
    :value="items" 
    lazy
    paginator
    :rows="15"
    :total-records="totalRecords"
    :loading="loading"
    @page="$emit('page-change', $event)"
    responsive-layout="scroll"
    showGridlines
  >
    <Column 
      v-for="col in columns" 
      :key="col.field" 
      :field="col.field" 
      :header="col.header"
    >
      <template #body="slotProps">
        <slot :name="col.field" :data="slotProps.data">
          {{ slotProps.data[col.field] }}
        </slot>
      </template>
    </Column>

    <Column
      v-if="showActions"
      header="Actions"
      style="min-width: 12rem"
      :exportable="false"
    >
      <template #body="slotProps">
        <div class="flex gap-2">
          <slot name="actions" :data="slotProps.data">
            <Button 
              icon="pi pi-pencil" 
              class="p-button-text p-button-warning" 
              @click="$emit('edit', slotProps.data)" 
            />
            <Button 
              icon="pi pi-trash" 
              class="p-button-text p-button-danger" 
              @click="$emit('delete', slotProps.data)" 
            />
          </slot>
        </div>
      </template>
    </Column>
  </DataTable>
</template>
