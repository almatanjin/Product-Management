<template>
  <div class="flex justify-center items-center min-h-[800px]">
    <div>
      <div class="pb-4 flex justify-center text-3xl text-slate-700">
        SIGN IN
      </div>
      <div
        class="border-solid border-2 rounded-md border-slate-100 min-h-[400px] min-w-[400px] p-16"
      >
        <div class="pb-8">
          <InputText v-model="state.email" type="text" placeholder="Email" />
        </div>
        <div class="pb-12">
          <InputText
            v-model="state.password"
            type="text"
            placeholder="Password"
          />
        </div>
        <div class="flex justify-center pb-8">
          <Button label="LOGIN" raised class="button" @click="onLogin" />
        </div>
        <div class="flex justify-center">
          Do you have an account?
          <RouterLink to="/register" class="pl-2 text-blue-500">
            Sign up
          </RouterLink>
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
  email: string;
  password: string;
}

const router = useRouter();

const state: State = reactive({
  email: "",
  password: "",
});

const onLogin = async () => {
  try {
    const response = await http.post("/login", state);
    const token = response.data.token;

    if (token) {
      localStorage.setItem("authToken", token); 
      router.push("/"); 
    }

    // router.push('/');
  } catch (err) {
    console.error("Error login:", err);
  }
};
</script>

<style lang="css">
.button {
  background-color: #6660df !important;
  border: 2px solid #6660df !important;
}
</style>
