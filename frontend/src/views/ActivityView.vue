<template>
    <!-- <ErrorMessage :status="status" /> -->
    <div class="mb-6">
        <div class="text-3xl">
            Activités de toutes les classes
        </div>
        <div class="text-l">
            Les activités en cours et passées sont accessibles des étudiants concernés. En cachant une activité, les
            étudiants ne pourront plus y accéder.
        </div>
    </div>
    <div class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-2">Quiz</th>
                                    <th scope="col" class="px-6 py-2">Questions</th>
                                    <th scope="col" class="px-6 py-2">Classe</th>
                                    <th scope="col" class="px-6 py-2">Durée</th>
                                    <th scope="col" class="px-6 py-2">Modifié</th>
                                    <th scope="col" class="px-6 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in activities"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="px-6 py-4 font-medium">{{ activity.quiz.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ activity.quiz.questions }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ activity.roster.data.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ secondsToMinutes(activity.duration) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ timestampToDateAndHour(activity.updated_at) }} </td>
                                    <td class="whitespace-nowrap px-6 py-6 h-full">
                                        <div v-if="activity.status === 'idle'" class="flex items-center space-x-1">
                                            <TrashIcon />
                                            <OpenActivityIcon />
                                        </div>
                                        <div v-else-if="activity.status === 'opened'" class="flex items-center space-x-1">
                                            <PlayActivityIcon />
                                            <CloseActivityIcon />
                                        </div>
                                        <div v-else-if="activity.status === 'running'" class="flex items-center space-x-1">
                                            <RealTimeProgressionIcon />
                                            Time
                                        </div>
                                        <div v-else class="flex items-center space-x-1">
                                            <ShowHideIcon />
                                            <ShowResultsIcon />
                                            <RealTimeProgressionIcon />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import ShowHideIcon from '@/components/icons/ShowHideIcon.vue';
import ShowResultsIcon from '@/components/icons/ShowResultsIcon.vue';
import RealTimeProgressionIcon from '@/components/icons/RealTimeProgressionIcon.vue';
import PlayActivityIcon from '@/components/icons/PlayActivityIcon.vue';
import OpenActivityIcon from '@/components/icons/OpenActivityIcon.vue';
import CloseActivityIcon from '@/components/icons/CloseActivityIcon.vue';
import TrashIcon from '@/components/icons/TrashIcon.vue';


import ErrorMessage from '@/components/StatusError.vue';
import { useActivityStore } from '../stores/activity';


const activityStore = useActivityStore();
const activities = computed(() => activityStore.allActivities);

onMounted(async () => {
    const status = await activityStore.fetchAllActivities();
    console.log(activities.value);
});

const secondsToMinutes = (seconds) => {
    const time = Math.ceil(seconds / 60);
    const unite = time > 1 ? 'minutes' : 'minute';
    return `${time} ${unite}`;
};

const timestampToDateAndHour = (timestamp) => {
    const date = new Date(timestamp);
    return `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()} 
    ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
};

</script>