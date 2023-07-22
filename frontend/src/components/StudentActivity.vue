<template>
    <div class="mb-6">
        <div class="text-3xl">
            Voici les activités auxquelles vous avez accès.
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
                                    <th scope="col" class="px-6 py-2">Classe</th>
                                    <th scope="col" class="px-6 py-2">Enseignant</th>
                                    <th scope="col" class="px-6 py-2">Quiz</th>
                                    <th scope="col" class="px-6 py-2">Modifié</th>
                                    <th scope="col" class="px-6 py-2">Note</th>
                                    <th scope="col" class="px-6 py-2">Status</th>
                                    <th scope="col" class="px-6 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in activities"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="px-6 py-4 font-medium">{{ activity.roster.data.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ activity.roster.data.teacher.name
                                    }}</td>
                                    <td class="px-6 py-4 font-medium">{{ activity.quiz.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ timestampToDateAndHour(activity.updated_at) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ activity.mark }} </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        <div class="p-1" v-if="activity.status == 'idle'" variant="info">En attente</div>
                                        <div class="p-1" v-else-if="activity.status == 'opened'" variant="primary">Ouverte
                                        </div>
                                        <div class="p-1" v-else-if="activity.status == 'finished'" variant="success">Terminée
                                        </div>
                                        <div class="p-1" v-else-if="activity.status == 'started'" variant="warning">Démarrée
                                        </div>
                                        <div class="p-1" v-else-if="activity.status == 'running'" variant="warning">En cours
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-6 h-full">
                                        <div v-if="activity.status == 'opened' || activity.status == 'running'">
                                            <RouterLink :to="`/activities/${activity.id}/questions/1`" v-tooltip="'Cliquer pour commencer l\'activité.'">
                                                <PlayActivityIcon />
                                            </RouterLink>
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
import { onMounted, ref } from 'vue';
import PlayActivityIcon from '@/components/icons/PlayActivityIcon.vue';
import { useActivityStore } from '@/stores/activity';


const activityStore = useActivityStore();
const activities = ref([]);

onMounted(async () => {
    const [status, data] = await activityStore.fetchConnectedStudentActivities();
    if (status === 200) {
        activities.value = data.data;
    }
});

const timestampToDateAndHour = (timestamp) => {
    const date = new Date(timestamp);
    return `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getFullYear()} 
    ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
};

</script>