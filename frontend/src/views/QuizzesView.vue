<template>
    <!-- <div v-for="item in listItems.keywords">
        {{ item.name }}
    </div> -->
</template>

<script setup>
import { ref } from 'vue';

const listItems = ref([]);

async function getData() {
    const res = await fetch("http://localhost:8000/api/user", {
        credentials: 'include',
    });
    const finalRes = await res.json();
    listItems.value = finalRes;
    console.log(finalRes);
}

getData()
</script>