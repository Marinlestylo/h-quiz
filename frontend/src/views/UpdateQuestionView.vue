<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Modification de la question {{ $route.params.id }}
        </div>
        <div>
            <RouterLink to="/questions" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Retour vers toutes les questions
            </RouterLink>
        </div>
    </div>

    <div v-if="!showQuestion">
        <AlertPopup v-model:message="message" alertType="error" />
    </div>
    <div v-else>
        <QuestionEditor v-model:question="question" usage="update" />
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useQuestionStore } from '../stores/question';
import AlertPopup from '../components/AlertPopup.vue';
import QuestionEditor from '../components/QuestionEditor.vue';

const questionStore = useQuestionStore();
const message = ref('');
const showQuestion = ref(false);


const question = ref({
    id: null,
    name: '',
    content: '',
    keywords: [],
    difficulty: '',
    validation: '',
    type: '',
    explanation: '',
    option: {},
    public: false,
    points: 0,
});

onMounted(async () => {
    const route = useRoute();
    const data = await questionStore.fetchQuestionById(route.params.id);
    if (data.length === 0) {
        message.value = 'La question demandée n\'existe pas.';
        return;
    } else {
        showQuestion.value = true;
        question.value.id = data.id;
        question.value.name = data.name;
        question.value.content = data.content;
        question.value.keywords = data.keywords;
        question.value.difficulty = data.difficulty;
        question.value.validation = data.validation;
        question.value.type = data.type;
        question.value.explanation = data.explanation;
        question.value.option = data.options;
        question.value.public = data.is_public;
        question.value.points = data.points;
    }
});
</script>