<template>
    <div class="flex justify-center items-center min-h-[800px]">
        <div>
            <div class="mt-24 pb-6 flex justify-center text-3xl text-slate-700">
                Edit CUSTOMER
            </div>
            <div class="border-solid border-2 rounded-md border-slate-100 min-h-[400px] w-full md:w-[600px] p-16">
                <div class="pb-8 flex justify-center gap-4">
                        <div>
                            <InputText v-model="state.first_name" type="text" placeholder="First Name" style="width: 100%" />
                        </div>
                        <div>
                            <InputText v-model="state.last_name" type="text" placeholder="Last Name" style="width: 100%" />
                        </div>
                </div>
                <div class="pb-8 flex justify-center gap-4">
                    <div>
                            <InputText v-model="state.email" type="text" placeholder="Email" style="width: 100%" />
                        </div>
                        <div>
                            <InputText v-model="state.phone" type="text" placeholder="Phone" style="width: 100%" />
                        </div>
                </div>

                <div class="pb-8 flex justify-center">
                    <InputText type="text" v-model="state.address" placeholder="address" style="width: 100%;" />
                </div>
                <div class="flex justify-center pb-4">
                    <Button label="Edit" raised class="button" @click="onEdit" />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { reactive, ref } from "vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import http from "@/services/api.js";
import { useRouter } from "vue-router";
import { useRoute } from 'vue-router';


interface State {
    first_name: string;
    last_name: string;
    email: string;
    phone: number | string;
    address : string;
}

const state: State = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    address : '',
});

const router = useRouter();
const route = useRoute();

const id = route.params.id;


const getCustomers = async () => {
  http.get(`/customers/${id}`).then((response) => {
    state.first_name = response.data.data.first_name;
    state.last_name = response.data.data.last_name;
    state.email = response.data.data.email;
    state.phone = response.data.data.phone;
    state.address = response.data.data.address;
  }).catch(()=> {
    router.push('/customers')
  });
};

getCustomers();

const onEdit = async () => {
    try {
        const response = await http.put(`/customers/${id}`, state);

        if (response.data.success) {
            router.push("/customers");

        }

    } catch (err) {
        console.error("Error:", err);
    }
};

</script>

<style lang="css">
.button {
    background-color: #6660df !important;
    border: 2px solid #6660df !important;
}
</style>
