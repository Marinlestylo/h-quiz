<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="flex flex-col h-screen justify-between">
    <header class="w-full bg-gray-900 p-4">
      <nav class="text-white flex items-center">
        <a href="https://heig-vd.ch" target="_blank">
          <img src="@/assets/images/logo-heig-vd.svg" alt="Logo" class="h-12 inline-block" />
        </a>
        <div>
          <img src="@/assets/images/quiz.svg" alt="Logo" class="h-12 inline-block" />
        </div>
        <RouterLink to="/" class="ml-4 hover:text-gray-300">Home</RouterLink>
        <RouterLink to="/about" class="ml-4 hover:text-gray-300">About</RouterLink>
      </nav>
  </header>

  <div class="mb-auto mx-auto mt-16">
    <RouterView />
  </div>

  <footer>
    <div class="w-full bg-gray-900 p-4 flex justify-end">
      <div>
          <img src="@/assets/images/logo-vd.svg" alt="Logo" class="h-16 inline-block mr-4" />
        </div>
        <div>
          <img src="@/assets/images/logo-hes-so.svg" alt="Logo" class="h-16 inline-block" />
        </div>
    </div>
  </footer>
  </div>
</template>
