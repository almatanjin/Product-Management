<template>
  <div class="flex justify-center items-center min-h-[800px]">
    <div>
      <div class="mt-24 pb-6 flex justify-center text-3xl text-slate-700">
        ADD PRODUCT ORDER
      </div>
      <div
        class="border-solid border-2 rounded-md border-slate-100 min-h-[400px] w-full md:w-[600px] p-16"
      >
        <div>
          <img
            :src="`http://127.0.0.1/storage/${product.image}`"
            :alt="product.image"
            class="w-48 rounded pb-8"
          />
        </div>
        <div class="pb-8 flex justify-center gap-4">
          <Select
            v-model="selectedStatus"
            :options="status"
            optionLabel="name"
            placeholder="Select Status"
            class="w-full"
          />
        </div>

        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.price"
            type="number"
            placeholder="Total Price"
            style="width: 100%"
          />
        </div>

        <div class="pb-8 flex justify-center gap-4">
          <InputNumber
            v-model="state.quantity"
            inputId="stacked-buttons"
            showButtons
            fluid
          />
        </div>

        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.total_amount"
            type="number"
            placeholder="Total Ammount"
            style="width: 100%"
          />
        </div>

        <div class="flex justify-center pb-4">
          <Button label="Create" raised class="button" @click="onCreate" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { computed, reactive, ref } from "vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import http from "@/services/api.js";
import { useRouter } from "vue-router";
import Select from "primevue/select";
import InputNumber from "primevue/inputnumber";
import { useRoute } from "vue-router";

interface State {
  status: string;
  price: number;
  quantity: number;
  total_amount: number
}

const route = useRoute();
const id = route.params.id;
const state: State = reactive({
  status: "",
  price: 0,
  quantity: 1,
  total_amount: 0
});

const selectedStatus = ref();
const customers = ref();
const product = ref();
const selectedCustomer = ref();

const status = ref([
  { name: "Pending", code: "pending" },
  { name: "Processing", code: "processing" },
  { name: "Shipped", code: "shipped" },
]);

const router = useRouter();

const customerOptions = computed(() => {
  if (customers.value) {
    return customers.value.map((customer) => {
      return {
        name: `${customer.first_name} ${customer.last_name}`,
        id: customer.id,
      };
    });
  }
});

const onCreate = async () => {
  try {
    state.customer_id = selectedCustomer.value.id;
    state.status = selectedStatus.value.name;
    const response = await http.post("/orders", state);

    if (response.data.success) {
      router.push("/orders");
    }
  } catch (err) {
    console.error("Error:", err);
  }
};

const getCustomers = async () => {
  http.get(`/customers`).then((response) => {
    customers.value = response.data.data;
  });
};

getCustomers();

const getProduct = async () => {
  http.get(`/products/${id}`).then((response) => {
    product.value = response.data.data;
    console.log(product.value);
  });
};

getProduct();
</script>

<style lang="css">
.button {
  background-color: #6660df !important;
  border: 2px solid #6660df !important;
}
</style>
