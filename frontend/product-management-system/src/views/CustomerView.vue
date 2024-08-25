<template>
  <div>
    <HomeLayout download_url="/export-customer" import_url="/import-customers" title="CUSTOMERS" create_url="/customer-create">
      <template #dataTable>
        <DataTable
          :value="data"
          tableStyle="min-width: 50rem"
          scrollable
          scrollHeight="650px"
        >
          <Column field="first_name" header="First Name"></Column>
          <Column field="last_name" header="Last Name"></Column>
          <Column field="email" header="Email"></Column>
          <Column field="phone" header="Phone"></Column>
          <Column field="address" header="Address"></Column>
          <Column style="flex: 0 0 4rem" header="Action">
            <template #body="{data}">
              <div>
                <Button label="Edit" class="button" @click="router.push(`/customer-edit/${data.id}`)" />
                <Button label="Delete" class="delete ml-0 md:ml-4 mt-2 md:mt-0"   @click="onClick(data)"/>
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

const getCustomers = async () => {
  http.get(`/customers`).then((response) => {
    data.value = response.data.data;
  });
};

getCustomers();

const onClick = async (customer) => {
  visible.value = true;
  id.value = customer.id;
};

const onDelete = async () => {
  http.delete(`/customers/${id.value}`).then(() => {
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
