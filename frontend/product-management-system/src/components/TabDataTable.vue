
<template>
    <div class="card">
        <div class="flex justify-center mb-6">
            <SelectButton v-model="selectedOption" :options="options" optionLabel="label" dataKey="label" />
        </div>
        {{ selectedOption }}
        <!-- <DataTable :value="products" :size="size.value" tableStyle="min-width: 50rem">
            <Column field="code" header="Code"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="category" header="Category"></Column>
            <Column field="quantity" header="Quantity"></Column>
        </DataTable> -->

         <!-- {{ value}} -->
    </div>
</template>

<script setup lang="ts">
import { ref, defineProps } from 'vue';
import SelectButton from 'primevue/selectbutton';


defineProps<{
  value?: string
}>();

const selectedOption = ref('');


const options = ref([
    { label: 'Products', value: 'products' },
    { label: 'Customers', value: 'customers' },
    { label: 'Orders', value: 'orders' }
]);

</script>
