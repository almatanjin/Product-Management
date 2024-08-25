<template>
  <div class="flex justify-center items-center min-h-[800px]">
    <div>
      <div class="mt-24 pb-6 flex justify-center text-3xl text-slate-700">
        ADD ORDER
      </div>
      <div
        class="border-solid border-2 rounded-md border-slate-100 min-h-[400px] w-full md:w-[600px] p-16"
      >
        <div class="pb-8 flex justify-center gap-4">
          <Select
            v-model="selectedCustomer"
            :options="customerOptions"
            optionLabel="name"
            placeholder="Select Customer"
            class="w-full"
          />
        </div>

        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.total_amount"
            type="number"
            placeholder="Total Price"
            style="width: 100%"
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

interface State {
  status: string;
  total_amount: number;
  customer_id: number | null;
}

const state: State = reactive({
  status: "",
  total_amount: 0,
  customer_id: null,
});

const selectedStatus = ref();
const customers = ref();
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
</script>

<style lang="css">
.button {
  background-color: #6660df !important;
  border: 2px solid #6660df !important;
}
</style>
