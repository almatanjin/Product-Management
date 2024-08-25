<template>
    <div>
      <div
        class="border-solid border-2 rounded-md border-slate-100 min-h-[500px] w-full md:w-[600px] p-16"
      >
        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.name"
            type="text"
            placeholder="Name"
            style="width: 86%"
          />
        </div>
        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.email"
            type="text"
            placeholder="Email"
            style="width: 86%"
          />
        </div>
        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.password"
            type="text"
            placeholder="Password"
            style="width: 86%"
          />
        </div>
        <div class="pb-8 flex justify-center">
          <InputText
            v-model="state.password_confirmation"
            type="text"
            placeholder="Confirm Password"
            style="width: 86%"
          />
        </div>
        <div class="flex justify-center pb-4">
          <Button label="Register" raised class="button" @click="$emit('onRegister', state)" />
        </div>
        <div class="flex justify-center">
          Allready have an account?
          <RouterLink to="/login" class="pl-2 text-blue-500"> Sign In </RouterLink>
        </div>
      </div>
    </div>
  </template>
  <script setup lang="ts">
  import { reactive } from "vue";
  import InputText from 'primevue/inputtext'
  import Button from 'primevue/button';

  
  // define props
  const props = defineProps<{
    name:string;
    email: string;
    password: string;
    password_confirmation: string;
  }>();
  
  // define events
  const emits = defineEmits<{
    (e: "onRegister", value: State): void;
  }>();
  
  interface State {
    name:string;
    email: string;
    password: string;
    password_confirmation: string;
  }
  
  const state: State = reactive({
    name: props.name,
    email: props.email,
    password: props.password,
    password_confirmation: props.password_confirmation,
  });
  </script>
  
  <style lang="css">
  .button {
    background-color: #6660df !important;
    border: 2px solid #6660df !important;
  }
  </style>
  