<template>
    <ActivityIconAction @click="debug"/>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useActivityStore } from '../stores/activity';
import ShowHideIcon from '@/components/icons/ShowHideIcon.vue';
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

const ActivityIconAction = computed(() => {
    switch (props.action) {
        case 'show':
            return ShowHideIcon;
        case 'hide':
            return ShowHideIcon;
        case 'results':
            return ShowResultsIcon;
        case 'realTime':
            return RealTimeProgressionIcon;
        case 'play':
            return PlayActivityIcon;
        case 'open':
            return OpenActivityIcon;
        case 'close':
            return CloseActivityIcon;
        case 'delete':
            return TrashIcon;
        default:
            return ShowHideIcon;
    }
});

const debug = () => {
    console.log(props.activityId);
};



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