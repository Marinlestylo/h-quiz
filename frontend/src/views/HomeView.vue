<template>
  <main>
    <div class="mx-auto text-5xl font-bold text-center mb-10">Bienvenue sur Heig-Quiz</div>
    <div class="mx-auto text-3xl" v-show="!user.id">Veuillez vous connecter afin de profiter des fonctionnalités de l'application</div>
  </main>
</template>


<script setup>
import { computed } from 'vue';
import { useUserStore } from '@/stores/user';

const store = useUserStore();
const user = computed(() => store.user);
</script>