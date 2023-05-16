<template>
    <div v-if="isError">
        {{ errorMessage }}
    </div>
</template>


<script setup>
import { computed } from 'vue';

const props = defineProps(['status'])

const isError = computed(() => {
    return props.status >= 400 && props.status <= 599;
});

const errorMessage = computed(() => {
    if (props.status === 401) {
        return 'Vous devez être connecté pour accéder à cette page';
    } else if (props.status === 403) {
        return 'Vous n\'avez pas les droits pour accéder à cette page';
    }
});
</script>