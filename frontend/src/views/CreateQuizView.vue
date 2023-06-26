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
            quiz</button>

        <div v-show="successMessage !== ''" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ successMessage }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg @click="resetSuccess" class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
        <div v-show="errorMessage !== ''" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ errorMessage }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg @click="resetError" class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>

    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useQuizStore } from '../stores/quiz';

const quizStore = useQuizStore();

const quiz = ref('');
const errorMessage = ref('');
const successMessage = ref('');

async function createQuiz() {
    const [status, data] = await quizStore.createQuiz(quiz.value);
    if (status !== 200) {
        errorMessage.value = "Une erreur est survenue lors de la création du quiz";
        return;
    } else {
        quiz.value = '';
        successMessage.value = `Le quiz "${data.quiz}" a bien été créé"`;
    }
}


const resetSuccess = () => {
    successMessage.value = '';
}

const resetError = () => {
    errorMessage.value = '';
}
</script>