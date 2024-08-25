<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>
<template>
    <header>
    </header>
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600 mb-72 p-4 md:p-8">
      <div class="max-w-screen-xl flex flex-wrap items-center justify-center mx-auto p-4">
        <div class="items-center justify-between flex w-auto order-1" id="navbar-sticky">
          <ul class="flex p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            <li>
              <router-link 
                to="/" 
                class="block py-2 px-3 rounded md:p-0"
                :class="{ 
                  'text-blue-700': $route.path === '/', 
                  'text-gray-900 md:bg-transparent': $route.path !== '/'
                }"
                aria-current="page"
              >
                Products
              </router-link>
            </li>
            <li>
              <router-link 
                to="/customers" 
                class="block py-2 px-3 rounded md:p-0"
                :class="{ 
                  'text-blue-700': $route.path === '/customers', 
                  'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent': $route.path !== '/customers'
                }"
              >
                Customers
              </router-link>
            </li>
            <li>
              <router-link 
                to="/orders" 
                class="block py-2 px-3 rounded md:p-0"
                :class="{ 
                  'text-blue-700': $route.path === '/orders', 
                  'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent': $route.path !== '/orders'
                }"
              >
                Orders
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <RouterView />
  </template>
  

<style scoped>

</style>
