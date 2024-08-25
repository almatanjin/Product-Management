<template>
  <div class="flex justify-center min-h-[800px] pt-10">
    <div>
      <div class="flex justify-center mb-6 mt-40">
        <SelectButton
          v-model="selectedOption"
          :options="options"
          optionLabel="label"
          dataKey="label"
          @change="getData(selectedOption.value)"
        />
      </div>
      <div>
        <DataTable :value="data" tableStyle="min-width: 50rem" scrollable scrollHeight="700px">
          <Column  v-if="selectedOption.value == 'products'" field="name" header="Name"></Column>
          <Column v-if="selectedOption.value == 'products'" header="Image">
            <template #body="slotProps">
              <img
                :src="`http://127.0.0.1/storage/${slotProps.data.image}`"
                :alt="slotProps.data.image"
                class="w-24 rounded"
              />
            </template>
          </Column>
          <Column v-if="selectedOption.value == 'products'" field="description" header="Description"></Column>
          <Column v-if="selectedOption.value == 'products'" field="price" header="Price"></Column>
          <Column v-if="selectedOption.value == 'customers'" field="first_name" header="First Name"></Column>
          <Column v-if="selectedOption.value == 'customers'" field="last_name" header="Last Name"></Column>
          <Column v-if="selectedOption.value == 'customers'" field="email" header="Email"></Column>
          <Column v-if="selectedOption.value == 'customers'" field="phone" header="Phone"></Column>
          <Column v-if="selectedOption.value == 'customers'" field="address" header="Address"></Column>
          <Column v-if="selectedOption.value == 'orders'" header="Image">
            <template #body="slotProps">
              {{ slotProps }}
            </template>
          </Column>
          <Column v-if="selectedOption.value == 'orders'" field="total_amount" header="Total Amount"></Column>
          <Column v-if="selectedOption.value == 'orders'" field="status" header="Status"></Column>
          <Column style="flex: 0 0 4rem" header="Action">
                <template #body="{ data, frozenRow, index }">
                  {{ data }}
                    <Button type="button"  label="Edit" :icon="'pi pi-lock-open'"  />
                </template>
            </Column>

        </DataTable>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { reactive, ref } from "vue";
import http from "@/services/api.js";
import SelectButton from "primevue/selectbutton";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";

const data = ref();

const selectedOption = ref(
    {"label": "Products", "value": "products"}
);

const options = ref([
  { label: "Products", value: "products" },
  { label: "Customers", value: "customers" },
  { label: "Orders", value: "orders" },
]);

const getData = async (value = "products") => {
  http.get(`/${value}`).then((response) => {
    data.value = response.data.data;
    console.log(data.value);
  });
};

getData();
</script>

<style lang="css">
.button {
  background-color: #6660df !important;
  border: 2px solid #6660df !important;
}
</style>
