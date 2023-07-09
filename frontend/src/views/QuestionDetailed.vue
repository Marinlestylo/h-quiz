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
        <div v-else-if="activity.status === 'running'" class="">
            <div class="text-2xl mb-3">
                Question {{ currentQuestionNumber }} / {{ activity.quiz.questions }} :
                <span v-if="questions[currentQuestionNumber - 1]">
                    {{ questions[currentQuestionNumber - 1].name }}
                </span>
            </div>
            <div class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-4xl" v-if="questions[currentQuestionNumber - 1]">
                <div v-if="questions[currentQuestionNumber - 1].type === 'short-answer'">
                    <ShortAnswer :content="questions[currentQuestionNumber - 1].content" v-model:shortAnswer="answer" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'multiple-choice'">
                    <MultipleChoice :content="questions[currentQuestionNumber - 1].content" v-model:selected="choices" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'fill-in-the-gaps'">
                    <FillGaps :content="questions[currentQuestionNumber - 1].content" :option="questions[currentQuestionNumber - 1].options" v-model:selected="choices" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'code'">
                    <CodeQuestion :content="questions[currentQuestionNumber - 1].content" v-model:code="answer" />
                </div>
                <div v-else>
                    Ce type de question n'est pas encore supporté.
                </div>
            </div>
            <!-- <div class="flex justify-between w-full">
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
            </div> -->
            <div class="w-full flex justify-between mt-4">
                <button @click="previousQ" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Précédent</button>
                <button @click="nextQ" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Suivant</button>
            </div>
            <button @click="debug">WWWWWWWWWW</button>
        </div>
        <div v-else>

        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onBeforeMount, ref } from 'vue';
import { useActivityStore } from '../stores/activity';
import { useRoute } from 'vue-router';
import { RouterLink } from 'vue-router';
import router from '../router';
import BacktickText from '../components/questions/BacktickText.vue';
import ShortAnswer from '../components/questions/ShortAnswer.vue';
import MultipleChoice from '../components/questions/MultipleChoice.vue';
import FillGaps from '../components/questions/FillGaps.vue';
import CodeQuestion from '../components/questions/CodeQuestion.vue';

const activityStore = useActivityStore();
const activity = computed(() => activityStore.currentlyUsedActivity.activity);
const questions = computed(() => activityStore.currentlyUsedActivity.questions);
const unauthorized = ref(false);
const route = useRoute();
const currentQuestionNumber = +route.params.questionId;
const choices = ref([]);
const answer = ref('');
const next = computed(() => {
    return +route.params.questionId + 1;
});
const previous = computed(() => {
    return +route.params.questionId - 1;
});


onBeforeMount(async () => {
    // console.log('mounted');
    if (!Object.entries(activityStore.currentlyUsedActivity.activity).length || activityStore.currentlyUsedActivity.activity.id !== +route.params.id) {
        // console.log('fetching');
        const status = await activityStore.fetchOneActivity(route.params.id);
        if (status !== 200) {
            unauthorized.value = true;
            return;
        }
    }
    fetchQuestion(+route.params.questionId);
});


const debug = () => {
    // console.log(questions.value[currentQuestionNumber - 1]);
    // console.log(choices.value);
    // console.log(questions.value[currentQuestionNumber - 1].option)
}

const fetchQuestion = async (id) => {
    if (id < 1 || id > activity.value.quiz.questions) {
        router.push(`/404`);
        return;
    }

    if (questions.value[id - 1]) {
        return;
    }
    // console.log('fetching question');

    await activityStore.fetchActivityQuestion(route.params.id, id);
}

const nextQ = () => {
    if (+route.params.questionId === activity.value.quiz.questions) {
        return;
    }
    const next = +route.params.questionId + 1;
    router.push(`/activities/${route.params.id}/questions/${next}`);
}

const previousQ = () => {
    if (+route.params.questionId === 1) {
        return;
    }
    const next = +route.params.questionId - 1;
    router.push(`/activities/${route.params.id}/questions/${next}`);
}
</script>