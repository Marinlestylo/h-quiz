<template>
    <main>
        <div>
            <h1 v-if="user" class="text-3xl font-bold">{{user.name}}</h1>
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" @click="logout">
                logout
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
import { useUserStore } from '../stores/user';
const store = useUserStore();
const user = computed(() => store.user);

onMounted(async () => {
    await store.fetchUser();
});


function redirect() {
    window.location.href = 'http://localhost:8000/api/login?redirect=http://localhost:5173';
}

function logout() {
    window.location.href = 'http://localhost:8000/api/logout';
}
</script>