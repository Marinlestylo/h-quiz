<template>
    <div v-if="unauthorized">
        Vous n'avez pas accès à cette page. Seul le professeur responsable de cette activité peut la consulter.
    </div>

    <div v-else>
        <div class="text-2xl mb-3">
            Résultats de l'activité n° {{ activity.id }}
        </div>
        <div v-for="(question, index) in questions"
            class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-3xl mb-10">
            <div class="text-lg">
                Question numéro {{ index + 1 }} : {{ question.name }}
            </div>
            <div>
                <div v-if="question.type === 'short-answer'">
                    <ShortAnswer :content="question.content" :shortAnswer="question.validation.expected" />
                </div>
                <div v-else-if="question.type === 'multiple-choice'">
                    <MultipleChoice :content="question.content" :selected="question.validation" />
                </div>
                <div v-else-if="question.type === 'fill-in-the-gaps'">
                    <FillGaps :content="question.content" :option="question.options" :selected="question.validation" />
                </div>
                <div v-else-if="question.type === 'code'">
                    <CodeQuestion :content="question.content" v-model:code="question.validation.expected" />
                </div>
                <div v-else-if="question.type === 'long-answer'">
                    <LongAnswer :content="question.content" :answer="question.validation.expected" />
                </div>
                <div v-else>
                    Ce type de question n'est pas encore supporté.
                </div>
            </div>
            <div class="mb-3">
                Explication : {{ question.explanation ? question.explanation : "Aucune explication n'a été fournie." }}
            </div>
            <div class="mb-3">
                Nombre de réponses correctes : {{ question.statistics.correct_answers }} / {{
                    question.statistics.correct_answers + question.statistics.incorrect_answers +
                    question.statistics.missing_answers }}
            </div>
            <div class="mb-3">
                Nombre de réponses manquantes : {{ question.statistics.missing_answers }} / {{
                    question.statistics.correct_answers + question.statistics.incorrect_answers +
                    question.statistics.missing_answers }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useActivityStore } from '@/stores/activity';
import { useRoute } from 'vue-router';
import { useUserStore } from '@/stores/user';
import ShortAnswer from '@/components/questions/ShortAnswer.vue';
import MultipleChoice from '@/components/questions/MultipleChoice.vue';
import FillGaps from '@/components/questions/FillGaps.vue';
import CodeQuestion from '@/components/questions/CodeQuestion.vue';
import LongAnswer from '@/components/questions/LongAnswer.vue';

const activityStore = useActivityStore();
const activity = computed(() => activityStore.currentlyUsedActivity.activity);
const userStore = useUserStore();
const user = computed(() => userStore.user);

const route = useRoute();
const unauthorized = ref(false);
const notFinished = ref(false);
const questions = ref([]);

onMounted(async () => {
    const status = await activityStore.fetchOneActivity(route.params.id);
    if (status !== 200) {
        unauthorized.value = true;
        return;
    }
    const [s, d] = await activityStore.activityResultsQuestions(route.params.id);
    questions.value = d.data;
});
</script>