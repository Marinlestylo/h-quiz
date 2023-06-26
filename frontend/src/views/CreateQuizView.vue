<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Créer un nouveau quiz
        </div>
    </div>

    <div class="w-full">
        <div class="mb-6 flex items-center">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Nom du quiz</label>
            <input type="text" id="name" v-model="quiz"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Les bases du python" autocomplete="off">
        </div>

        <button @click="createQuiz"
            class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto mb-4">Création du
            quiz
        </button>

        <AlertPopup v-model:message="successMessage" alertType="success" />
        <AlertPopup v-model:message="errorMessage" alertType="error" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useQuizStore } from '../stores/quiz';
import AlertPopup from '@/components/AlertPopup.vue';

const quizStore = useQuizStore();

const quiz = ref('');
const errorMessage = ref('');
const successMessage = ref('');

async function createQuiz() {
    if (quiz.value === '') {
        errorMessage.value = "Le nom du quiz ne peut pas être vide";
        return;
    }
    const [status, data] = await quizStore.createQuiz(quiz.value);
    if (status !== 200) {
        errorMessage.value = "Une erreur est survenue lors de la création du quiz";
        return;
    } else {
        quiz.value = '';
        successMessage.value = `Le quiz "${data.quiz}" a bien été créé"`;
    }
}
</script>