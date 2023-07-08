<template>
    <!-- Unauthorized -->
    <div v-if="unauthorized" class="text-3xl">
        Cette activité n'existe pas ou vous n'avez pas les droits pour la consulter.
    </div>
    <div v-else class="flex flex-col items-center justify-center">
        <!-- Incorrect status -->
        <div v-if="activity.status === 'idle'" class="text-3xl">
            Cette activité n'est pas encore ouverte.
        </div>
        <div v-else-if="activity.status === 'finished'" class="text-3xl">
            Cette activité est terminée, vous ne pouvez plus la rejoindre.
        </div>
        <div v-else-if="activity.status === 'opened'" class="text-3xl">
            Veuillez patienter le temps que le professeur lance l'activité.
        </div>
        <div v-else-if="activity.status === 'running'">
            <div class="flex justify-between w-full">
                <RouterLink :to="`/activities/${$route.params.id}/questions/${previous}`"
                    class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded"
                    v-show="+$route.params.questionId > 1">
                    Précédent
                </RouterLink>
                <RouterLink :to="`/activities/${$route.params.id}/questions/${next}`"
                    class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded"
                    v-show="+$route.params.questionId !== activity.quiz.questions">
                    Suivant
                </RouterLink>
            </div>
        </div>
        <div v-else>

        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useActivityStore } from '../stores/activity';
import { useRoute } from 'vue-router';
import { RouterLink } from 'vue-router';

const activityStore = useActivityStore();
const activity = ref({});

const unauthorized = ref(false);
const questions = ref([]);
const route = useRoute();
const next = computed(() => {
    return +route.params.questionId + 1;
});
const previous = computed(() => {
    return +route.params.questionId - 1;
});

onMounted(async () => {
    const [status, data] = await activityStore.fetchOneActivity(route.params.id);
    if (status !== 200) {
        unauthorized.value = true;
        return;
    }
    // console.log(status, data);
    activity.value = data.data;
    console.log(activity.value);
});


const debug = () => {
    console.log(activity.value.quiz.questions);
}
</script>