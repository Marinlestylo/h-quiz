<script setup>
import { RouterView } from 'vue-router'
import MyFooter from '@/components/layouts/Footer.vue'
import NavBar from '@/components/layouts/Navbar.vue'
</script>

<template>
  <div class="flex flex-col h-screen justify-between">
    <NavBar/>

    <div class="mb-auto mx-auto mt-16">
      <RouterView />
    </div>

    <MyFooter/>
  </div>
</template>
