<template>
    <!-- SVG is comming from here : https://heroicons.com/ -->
    <div class="hover:cursor-pointer" @click="deleteActivity">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            v-tooltip="'Supprimer l\'activité'" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
    </div>
    <div class="absolute bottom-24 right-0">
        <AlertPopup v-model:message="message" :alertType="alertType" class="" />
    </div>
    
</template>

<script setup>
import { ref } from 'vue';
import { useActivityStore } from '../../stores/activity';
import AlertPopup from '../AlertPopup.vue';

const props = defineProps({
    activityId: Number
});

const activityStore = useActivityStore();

const message = ref('');
const alertType = ref('error');

async function deleteActivity() {
    const [status, data] = await activityStore.deleteActivity(props.activityId);
    message.value = data.message;
    if (status === 200) {
        alertType.value = 'success';
        await activityStore.fetchAllActivities();
    } else {
        alertType.value = 'error';
    }
    // Wait 5 seconds before removing the popup
    setTimeout(() => {
        message.value = '';
    }, 5000);
}

</script>