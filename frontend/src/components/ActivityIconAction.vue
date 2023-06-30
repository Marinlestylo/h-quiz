<template>
    <div v-tooltip="tooltip">
        <div v-if="path">
            <RouterLink :to="path">
                <ActivityIconAction />
            </RouterLink>
        </div>
        <div v-else class="hover:cursor-pointer">
            <ActivityIconAction @click="updateActivity" />
        </div>
    </div>
    <div class="absolute bottom-24 right-0">
        <AlertPopup v-model:message="message" :alertType="alertType" class="" />
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useActivityStore } from '../stores/activity';
import ShowActivityIcon from '@/components/icons/ShowActivityIcon.vue';
import HideActivityIcon from '@/components/icons/HideActivityIcon.vue';
import ShowResultsIcon from '@/components/icons/ShowResultsIcon.vue';
import RealTimeProgressionIcon from '@/components/icons/RealTimeProgressionIcon.vue';
import PlayActivityIcon from '@/components/icons/PlayActivityIcon.vue';
import OpenActivityIcon from '@/components/icons/OpenActivityIcon.vue';
import CloseActivityIcon from '@/components/icons/CloseActivityIcon.vue';
import TrashIcon from '@/components/icons/TrashIcon.vue';
import AlertPopup from '@/components/AlertPopup.vue';

const props = defineProps({
    activityId: Number,
    action: String
});

const path = ref('');
const tooltip = ref('');
const message = ref('');
const alertType = ref('error');

const ActivityIconAction = computed(() => {
    switch (props.action) {
        case 'show':
            tooltip.value = 'Rendre visible l\'activité aux étudiants';
            return ShowActivityIcon;
        case 'hide':
            tooltip.value = 'Cacher l\'activité aux étudiants';
            return HideActivityIcon;
        case 'results':
            path.value = '/quizzes';
            tooltip.value = 'Afficher les résultats';
            return ShowResultsIcon;
        case 'realTime':
            path.value = '/rosters';
            tooltip.value = 'Afficher la progression en temps réel';
            return RealTimeProgressionIcon;
        case 'play':
            tooltip.value = 'Lancer l\'activité';
            return PlayActivityIcon;
        case 'open':
            tooltip.value = 'Activité fermée. Cliquer pour l\'ouvrir.';
            return OpenActivityIcon;
        case 'close':
            tooltip.value = 'Activité disponible pour les étudiants. Appuyez pour la fermer.';
            return CloseActivityIcon;
    }
});

async function updateActivity() {
    const [status, data] = await activityStore.updateActivity(props.action, props.activityId);
    console.log(status, data);
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
    }, 500);
}



const activityStore = useActivityStore();

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