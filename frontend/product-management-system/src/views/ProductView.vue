<template>
  <div>
    <HomeLayout
      download_url="/export-product"
      import_url="/import-product"
      title="PRODUCTS"
      create_url="/create-product"
      file_name="product-csv.csv"
    >
      <template #dataTable>
        <DataTable
          :value="data"
          scrollable
          scrollHeight="650px"
          tableStyle="min-width: 20rem"
        >
          <Column field="name" header="Name"></Column>
          <Column header="Image">
            <template #body="slotProps">
              <div v-if="slotProps.data.image">
                <img
                  :src="`http://127.0.0.1/storage/${slotProps.data.image}`"
                  :alt="slotProps.data.image"
                  class="w-24 rounded"
                />
              </div>
              <div v-else>
                <img
                  :src="`https://www.gourmetwood.com/wp-content/plugins/post-type-x/core/img/no-default-thumbnail.png`"
                  :alt="slotProps.data.image"
                  class="w-24 rounded"
                />
              </div>
            </template>
          </Column>
          <Column field="description" header="Description"></Column>
          <Column field="price" header="Price"></Column>
          <Column class='action' header="Action">
            <template #body="{ data}">
              <div>
                <Button label="Edit" class="button" @click="router.push(`/product-edit/${data.id}`)" />
                <Button label="Delete" class="delete ml-0 md:ml-4 mt-2 md:mt-0" @click="onClick(data)" />
                <Button label="Order" class="upload ml-0 md:ml-4 mt-2 md:mt-0" @click="router.push(`/product-order/${data.id}`)" />
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
import Dialog from "primevue/dialog";
import Column from "primevue/column";
import Button from "primevue/button";
import HomeLayout from "@/components/HomeLayout.vue";
import router from "@/router";


const data = ref();
const visible = ref(false);
const id = ref();

const getProducts = async () => {
  http.get(`/products`).then((response) => {
    data.value = response.data.data;
  });
};

getProducts();

const onClick = async (product) => {
  visible.value = true;
  id.value = product.id;

};

const onDelete = async (product) => {
  http.delete(`/products/${id.value}`).then(() => {
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

.action {
  margin-left: 0 !important;
}
</style>
