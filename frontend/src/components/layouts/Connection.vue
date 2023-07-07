<template>
    <main class="ml-6">
        <div v-if="user" class="flex items-center">
            <h1 class="mr-6">{{user.name}}</h1>
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" @click="logout">
                Déconnexion
            </button>
        </div>
        <div class="w-full" v-if="!user">
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" @click="redirect">
                Se connecter
            </button>
            
        </div>
    </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useUserStore, backUrl, appUrl } from '../../stores/user';
const store = useUserStore();
const user = computed(() => store.user);



// console.log(import.meta.env);
// console.log(appUrl);
onMounted(async () => {
    await store.fetchUser();
    console.log('yo');
});


function redirect() {
    window.location.href = `${backUrl}/api/login?redirect=${appUrl}`;
}

function logout() {
    window.location.href = `${backUrl}/api/logout?redirect=${appUrl}`;
}
</script>