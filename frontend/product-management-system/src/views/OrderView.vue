<template>
  <div>
    <HomeLayout
      download_url="/export-order"
      import_url="/import-orders"
      title="ORDERS"
      file_name="order-csv.csv"
      create_url="/order-create"
    >
      <template #dataTable>
        <DataTable
          :value="data"
          tableStyle="min-width: 50rem"
          scrollable
          scrollHeight="650px"
        >
          <Column header="Customer">
            <template #body="{ data }">
              {{ data.customer.first_name }}
            </template>
          </Column>
          <Column field="total_amount" header="Total Amount"></Column>
          <Column field="status" header="Status"></Column>
          <Column style="flex: 0 0 4rem" header="Action">
            <template #body= {data}>
              <div>
                <Button label="Edit" class="button" @click="router.push(`/order-edit/${data.id}`)" />
                <Button label="Delete" class="delete ml-0 md:ml-4 mt-2 md:mt-0"  @click="onClick(data)"/>
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </HomeLayout>
    <Dialog
      v-model:visible="visible"
      header="Delete"
      :style="{ width: '25rem' }"
    >
      <span class="text-surface-500 dark:text-surface-400 block mb-8"
        >Do you want to delete?</span
      >
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="No"
          class="button"
          @click="visible = false"
        ></Button>
        <Button type="button" severity="secondary" label="Yes" @click="onDelete"></Button>
      </div>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import http from "@/services/api.js";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import HomeLayout from "@/components/HomeLayout.vue";
import Dialog from "primevue/dialog";
import router from "@/router";

const data = ref();
const visible = ref(false);
const id = ref();

const getOrders = async () => {
  http.get(`/orders`).then((response) => {
    data.value = response.data.data;
  });
};

getOrders();


const onClick = async (order) => {
  visible.value = true;
  id.value = order.id;
};

const onDelete = async () => {
  http.delete(`/orders/${id.value}`).then(() => {
       visible.value = false;
  });
};

</script>

<style lang="css">
.delete {
  background-color: red !important;
  border: 2px solid red !important;
}

.upload {
  background-color: #f9fafb !important;
  color: black !important;
  border: 1px solid #d1d5db !important;
}
</style>
