<template>
    <main class="ml-6">
        <div v-if="user.id" class="flex items-center">
            <h1 class="mr-6">{{user.name}}</h1>
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" @click="logout">
                Déconnexion
            </button>
        </div>
        <div class="w-full" v-else>
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" @click="redirect">
                Se connecter
            </button>
            
        </div>
    </main>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useUserStore } from '@/stores/user';
import * as utils from '@/utils.js';
const store = useUserStore();
const user = computed(() => store.user);

onMounted(async () => {
    await store.fetchUser();
});


function redirect() {
    window.location.href = `${utils.backUrl}/api/login?redirect=${utils.appUrl}`;
}

async function logout() {
    await store.logout();
    window.location.href = `${utils.backUrl}/api/logout?redirect=${utils.appUrl}`;
}
</script>