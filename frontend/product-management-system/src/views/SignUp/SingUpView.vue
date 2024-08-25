<template>
    <div class="flex justify-center items-center min-h-[800px]">
        <div>
            <div class="pb-4 flex justify-center text-3xl text-slate-700">
                SIGN UP
            </div>
            <SignUpform :name="state.name" :email="state.email" :password="state.password"
                :password_confirmation="state.password_confirmation" @on-register="onRegister" />
        </div>
    </div>
</template>
<script setup lang="ts">
import { reactive } from "vue";
import SignUpform from "@/components/SignUpform.vue";
import http from "@/services/api.js";
import { useRouter } from "vue-router";

interface State {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

const router = useRouter();

const state: State = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});



const onRegister = async (formValue: State) => {
    try {
        await http.post('/register', formValue);

        router.push('/login');

    } catch (err) {

        console.error('Error registering:', err);
    }

};
</script>

<style lang="css"></style>