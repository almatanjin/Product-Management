<template>
    <div class="flex justify-center items-center min-h-[800px]">
        <div>
            <div class="mt-24 pb-6 flex justify-center text-3xl text-slate-700">
                ADD CUSTOMER
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
                    <Button label="Create" raised class="button" @click="onCreate" />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { reactive } from "vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import http from "@/services/api.js";
import { useRouter } from "vue-router";



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

const onCreate = async () => {
    try {
        const response = await http.post('/customers', state);

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
