<template>
    <!-- Unauthorized -->
    <div v-if="unauthorized" class="text-3xl">
        Cette activité n'existe pas ou vous n'avez pas les droits pour la consulter.
    </div>
    <div v-else-if="finished" class="text-3xl">
        Vous avez terminé cet examen.
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
            <div v-if="activity.quiz.type === 'exam'">
                Examen
            </div>
            <div v-else-if="activity.quiz.type === 'exam' && activity.students_finished.some((s) => s.user_id === user.id)"
                class="text-3xl">
                Vous avez rendu cet examen
            </div>
            <div class="text-2xl mb-3">
                Question {{ currentQuestionNumber }} / {{ activity.quiz.questions }} :
                <span v-if="questions[currentQuestionNumber - 1]">
                    {{ questions[currentQuestionNumber - 1].name }}
                </span>
            </div>
            <div class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-4xl"
                v-if="questions[currentQuestionNumber - 1]">
                <div v-if="questions[currentQuestionNumber - 1].type === 'short-answer'">
                    <ShortAnswer :content="questions[currentQuestionNumber - 1].content" v-model:shortAnswer="answer" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'multiple-choice'">
                    <MultipleChoice :content="questions[currentQuestionNumber - 1].content" v-model:selected="choices" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'fill-in-the-gaps'">
                    <FillGaps :content="questions[currentQuestionNumber - 1].content"
                        :option="questions[currentQuestionNumber - 1].options" v-model:selected="choices" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'code'">
                    <CodeQuestion :content="questions[currentQuestionNumber - 1].content" v-model:code="answer" />
                </div>
                <div v-else-if="questions[currentQuestionNumber - 1].type === 'long-answer'">
                    <LongAnswer :content="questions[currentQuestionNumber - 1].content" v-model:answer="answer" />
                </div>
                <div v-else>
                    Ce type de question n'est pas encore supporté.
                </div>
            </div>
            <div class="w-full flex justify-between mt-4 items-center">
                <button @click="previousQ"
                    class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Précédent</button>
                <vue-countdown :time="activity.duration * 1000 - (Date.now() - Date.parse(activity.started_at))"
                    v-slot="{ hours, minutes, seconds }" class="font-medium text-xl" :transform="transformSlotProps" @end="finish">
                    {{ hours }}:{{ minutes }}:{{ seconds }}
                </vue-countdown>
                <div>
                    <button @click="nextQ" v-if="currentQuestionNumber !== activity.quiz.questions"
                        class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Suivant</button>
                    <button @click="finish" v-else
                        class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Terminer</button>
                </div>
            </div>
        </div>
        <div v-else>

        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useActivityStore } from '@/stores/activity';
import { useUserStore } from '@/stores/user';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import ShortAnswer from '@/components/questions/ShortAnswer.vue';
import MultipleChoice from '@/components/questions/MultipleChoice.vue';
import FillGaps from '@/components/questions/FillGaps.vue';
import CodeQuestion from '@/components/questions/CodeQuestion.vue';
import LongAnswer from '@/components/questions/LongAnswer.vue';

const router = useRouter();
const activityStore = useActivityStore();
const activity = computed(() => activityStore.currentlyUsedActivity.activity);
const questions = computed(() => activityStore.currentlyUsedActivity.questions);
const answers = computed(() => activityStore.currentlyUsedActivity.answers);
const unauthorized = ref(false);
const finished = ref(false);
const route = useRoute();
const currentQuestionNumber = +route.params.questionId;
const choices = ref([]);
const answer = ref('');
const userStore = useUserStore();
const user = computed(() => userStore.user);

onMounted(async () => {
    if (!Object.entries(activityStore.currentlyUsedActivity.activity).length || activityStore.currentlyUsedActivity.activity.id !== +route.params.id) {
        const status = await activityStore.fetchOneActivity(route.params.id);
        if (status !== 200) {
            unauthorized.value = true;
            return;
        }
    }
    if (activity.value.quiz.type === 'exam' && activity.value.students_finished.some((s) => s.user_id === user.value.id)) {
        finished.value = true;
        return;
    }
    fetchQuestionAndAnswer(+route.params.questionId);
});

const transformSlotProps = (props) => {
    const formattedProps = {};

    Object.entries(props).forEach(([key, value]) => {
        formattedProps[key] = value < 10 ? `0${value}` : String(value);
    });

    return formattedProps;
}

const timerFinish = () => {
    finish();

};

const saveAnswer = async () => {
    if (questions.value[currentQuestionNumber - 1].type === 'multiple-choice') {
        if (answers.value[currentQuestionNumber - 1] !== choices.value) {
            activityStore.addOneAnswer(currentQuestionNumber, choices.value);
            await activityStore.submitQuizAnswer(route.params.id, currentQuestionNumber, choices.value);
        }
    } else if (questions.value[currentQuestionNumber - 1].type === 'fill-in-the-gaps') {
        activityStore.addOneAnswer(currentQuestionNumber, choices.value);
        await activityStore.submitQuizAnswer(route.params.id, currentQuestionNumber, choices.value);
    } else {
        if (answers.value[currentQuestionNumber - 1] !== answer.value) {
            activityStore.addOneAnswer(currentQuestionNumber, answer.value);
            await activityStore.submitQuizAnswer(route.params.id, currentQuestionNumber, answer.value);
        }
    }

}

const fetchQuestionAndAnswer = async (id) => {
    // Mauvais ID
    if (id < 1 || id > activity.value.quiz.questions) {
        router.push(`/404`);
        return;
    }

    // pas besoin de fetch, c'est dans le store
    if (!questions.value[id - 1]) {
        await activityStore.fetchActivityQuestion(route.params.id, id);
    }

    if (questions.value[currentQuestionNumber - 1].type === 'multiple-choice' || questions.value[currentQuestionNumber - 1].type === 'fill-in-the-gaps') {
        if (answers.value[id - 1]) {
            choices.value = answers.value[id - 1];
            return;
        }
    } else {
        if (answers.value[id - 1]) {
            answer.value = answers.value[id - 1];
            return;
        }
    }
}

const nextQ = async () => {
    await saveAnswer();
    if (+route.params.questionId === activity.value.quiz.questions) {
        return;
    }
    const next = +route.params.questionId + 1;
    router.push(`/activities/${route.params.id}/questions/${next}`);
}

const finish = async () => {
    await saveAnswer();
    if (activity.value.quiz.type === 'exam') {
        await activityStore.studentFinishExam(route.params.id);
    }
    // router.push(`/activities`);
    window.location.href = '/activities';
}

const previousQ = async () => {
    await saveAnswer();
    if (+route.params.questionId === 1) {
        return;
    }
    const previous = +route.params.questionId - 1;
    router.push(`/activities/${route.params.id}/questions/${previous}`);
}
</script>