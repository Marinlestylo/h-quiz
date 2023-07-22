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
    <div class="absolute bottom-0 right-0">
        <AlertPopup v-model:message="message" :alertType="alertType" class="" />
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useActivityStore } from '@/stores/activity';
import ClosedEyeIcon from '@/components/icons/ClosedEyeIcon.vue';
import OpenEyeIcon from '@/components/icons/OpenEyeIcon.vue';
import ShowResultsIcon from '@/components/icons/ShowResultsIcon.vue';
import RealTimeProgressionIcon from '@/components/icons/RealTimeProgressionIcon.vue';
import PlayActivityIcon from '@/components/icons/PlayActivityIcon.vue';
import OpenActivityIcon from '@/components/icons/OpenActivityIcon.vue';
import CloseActivityIcon from '@/components/icons/CloseActivityIcon.vue';
import AlertPopup from '@/components/AlertPopup.vue';

const activityStore = useActivityStore();

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
            tooltip.value = 'L\'activité est cachée pour les étudiants. Cliqez pour la rendre visible.';
            return OpenEyeIcon;
        case 'hide':
            tooltip.value = 'L\'activité est visible par les étudiants. Cliquer pour la cacher.';
            return ClosedEyeIcon;
        case 'results':
            path.value = `/activities/${props.activityId}/results`;
            tooltip.value = 'Afficher les résultats de l\'activité.';
            return ShowResultsIcon;
        case 'realTime':
            path.value = `/activities/${props.activityId}/progression`;
            tooltip.value = 'Afficher la progression en temps réel de l\'activité.';
            return RealTimeProgressionIcon;
        case 'play':
            tooltip.value = 'Lancer l\'activité.';
            return PlayActivityIcon;
        case 'open':
            tooltip.value = 'Activité fermée. Cliquer pour l\'ouvrir.';
            return OpenActivityIcon;
        case 'close':
            tooltip.value = 'Activité disponible pour les étudiants. Cliquer pour la fermer.';
            return CloseActivityIcon;
    }
});

async function updateActivity() {
    const [status, data] = await activityStore.updateActivity(props.action, props.activityId);
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