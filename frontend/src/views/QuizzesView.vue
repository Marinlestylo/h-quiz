<template>
    <div v-if="!isLoading">
        <ErrorMessage :status="status" />
    </div>
    <div v-for="item in listItems.keywords">
        {{ item.name }}
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ErrorMessage from '@/components/StatusError.vue';
import { backUrl } from '@/stores/user';

const listItems = ref([]);
let status = ref(0);
const isLoading = ref(true);

async function getData() {
    const res = await fetch(`${backUrl}/api/keywords`, {
        credentials: 'include',
    });
    status.value = res.status;
    if (res.status === 200) {
        const finalRes = await res.json();
        listItems.value = finalRes;
    }
    isLoading.value = false;
}

getData()
</script>