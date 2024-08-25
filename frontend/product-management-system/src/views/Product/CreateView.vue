<template>
    <div class="flex justify-center items-center min-h-[800px]">
        <div>
            <div class="mt-24 pb-6 flex justify-center text-3xl text-slate-700">
                CREATE PRODUCT
            </div>
            <div class="border-solid border-2 rounded-md border-slate-100 min-h-[400px] w-full md:w-[600px] p-16">
                <div class="pb-8 flex justify-center">
                    <InputText v-model="state.name" type="text" placeholder="Name" style="width: 100%" />
                </div>
                <div class="pb-8 flex justify-center">
                    <InputText v-model="state.description" type="text" placeholder="Description" style="width: 100%" />
                </div>

                <div class="pb-8 flex justify-center">
                    <InputNumber v-model="state.price" placeholder="Price" inputId="stacked-buttons" showButtons
                        fluid />
                </div>
                <div class="pb-8" style="width: 100%">
                    <input
                        class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 p"
                        aria-describedby="file_input_help" id="file_input" type="file" @change="handleFileUpload">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF
                        (MAX. 800x400px).</p>

                </div>
                <div class="flex justify-center pb-4">
                    <Button label="Create Product" raised class="button" @click="onCreate" />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { reactive } from "vue";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Button from "primevue/button";
import http from "@/services/api.js";
import { useRouter } from "vue-router";



interface State {
    name: string;
    image: File | null;
    description: string;
    price: number;
}

const state: State = reactive({
    name: "",
    image: null,
    description: "",
    price: 0,
});

const router = useRouter();

const onCreate = async () => {
    try {
        const formData = new FormData();
        for (const key in state) {
            formData.append(key, state[key]);
        }

        const response = await http.post('/products', formData, {
            headers: {
                'Content-Type': 'multipart/form-data', // Required for file uploads
            },
        });

        if (response.data.success) {
            router.push("/");

        }

    } catch (err) {
        console.error("Error", err);
    }
};

const handleFileUpload = (event: any) => {
    state.image = event.target.files[0];
};
</script>

<style lang="css">
.button {
    background-color: #6660df !important;
    border: 2px solid #6660df !important;
}
</style>
