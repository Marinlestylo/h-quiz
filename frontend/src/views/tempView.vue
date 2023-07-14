<template>
    <button @click="redirect">WWWW</button>

</template>

<script setup>
import { useUserStore, backUrl, appUrl } from '@/stores/user';
import { useRoute } from 'vue-router';
const route = useRoute();

function redirect() {
    // console.log(route.params.id);
    window.location.href = `${backUrl}/api/debug/login/${route.params.id}`;
}

function logout() {
    window.location.href = `${backUrl}/api/logout?redirect=${appUrl}`;
}
</script>