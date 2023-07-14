<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Création d'une nouvelle question
        </div>
        <div>
            <RouterLink to="/questions" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Retour vers toutes les questions
            </RouterLink>
        </div>
    </div>

    <!-- Création d'une nouvelle question -->
    <div class="w-full">
        <QuestionEditor v-model:question="question" usage="create"/>
    </div>
</template>

<script setup>

import { ref } from 'vue';
import QuestionEditor from '../components/QuestionEditor.vue';

const question = ref({
    name: '',
    content: '',
    keywords: [],
    difficulty: '',
    validation: {},
    type: '',
    explanation: '',
});

</script>