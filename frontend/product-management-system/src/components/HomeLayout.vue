<template>
    <div class="mt-32 flex justify-center">
      <div class=" container">
        <Dialog
          v-model:visible="visible"
          modal
          header="Import"
          :style="{ width: '25rem' }"
        >
          <input
            class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-300 dark:border-gray-200 dark:placeholder-gray-600 p"
            aria-describedby="file_input_help"
            id="file_input"
            type="file"
            @change="handleFile"
          />
          <p
            class="mt-1 text-sm text-gray-500 dark:text-gray-300"
            id="file_input_help"
          >
            CSV
          </p>
          <div class="flex justify-end">
            <Button label="Ok" class="secondatButton" @click="onFileUpload" />
          </div>
        </Dialog>
        <div class="mt-4 mb-8 flex justify-between">
            <div class="pb-6 flex justify-center text-3xl text-slate-700">
               {{  props.title }}
            </div>
          <div><Menubar :model="items" breakpoint="560px" /></div>
        </div>
        <div>
           <slot name="dataTable" />
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from "vue";
  import http from "@/services/api.js";
  import Menubar from "primevue/menubar";
  import Button from "primevue/button";
  import Dialog from "primevue/dialog";
  import { useRouter } from "vue-router";
import { time } from "console";
import { title } from "process";
  

  const props = defineProps<{
    create_url?:string;
    download_url?: string;
    import_url?: string;
    edit_url?: string;
    title?: string | '';
    file_name?: string;
  }>();

  const router = useRouter();
  const visible = ref(false);
  const file = ref();
  
  const items = ref([
    {
      label: "Create",
      icon: "pi pi-file",
      command: () => {
        router.push(`${props.create_url}`);
      },
    },
    {
      label: "Import",
      icon: "pi pi-file",
      command: async () => {
        visible.value = true;
      },
    },
    {
      label: "Download",
      icon: "pi pi-file",
      command: async () => {
        try {
            console.log(props.download_url)
          const response = await http.get(`${props.download_url}`, {
            responseType: "blob",
          });
  
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", `${props.file_name ?? 'csv-file.csv'}`);
  
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        } catch (error) {
          console.error("There was an error downloading the file:", error);
        }
      },
    },
    {
      separator: true,
    },
  ]);
  
  const handleFile = (event: any) => {
    file.value = event.target.files[0];
  };
  
  const onFileUpload = async () => {
    try {
      const formData = new FormData();
      formData.append("import_csv", file.value);
  
      const response = await http.post(`${props.import_url}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data", // Required for file uploads
        },
      });
  
      if (response.data.success) {
        visible.value = false;
      }
    } catch (err) {
      console.error("Error login:", err);
    }
  };
  </script>
  
  <style lang="css">
  .delete {
    background-color: red !important;
    border: 2px solid red !important;
  }
  .secondatButton {
    background-color: #f9fafb !important;
    color: black !important;
    border: 1px solid #d1d5db !important;
  }

  .container{
      width: 1000px !important;
    }

  @media screen and (max-width: 800px) {
    .container{
      width: 700px !important;
    }
  }

  @media screen and (max-width: 640px) {
    .container{
      width: 500px !important;
    }
  }

  @media screen and (max-width: 480px) {
    .container{
      width: 400px !important;
    }
  }

  @media screen and (max-width: 380px) {
    .container{
      width: 300px !important;
    }
  }
  </style>
  