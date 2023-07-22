<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Créer un nouveau quiz
        </div>
        <div>
            <RouterLink to="/quizzes"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Retour vers tous les quiz
            </RouterLink>
        </div>
    </div>

    <div class="w-full">
        <div class="mb-6 flex items-center">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Nom du quiz</label>
            <input type="text" id="name" v-model="quiz"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Les bases du python" autocomplete="off">
        </div>
        <div class="mb-6 flex">
            <label for="difficulty" class="block mb-2 text-lg font-medium text-gray-900 w-44 mr-3">Type de quiz</label>
            <div>
                <select v-model="selectedType" id="difficulty"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option disabled selected value="">De quel type de quiz s'agit-il ?</option>
                    <option v-for="t in quizTypes" :key="t" v-bind:value="t">{{ t }}</option>
                </select>
            </div>
        </div>

        <button @click="createQuiz"
            class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto mb-4">Création du
            quiz
        </button>

        <AlertPopup v-model:message="message" :alertType="alertType" />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useQuizStore } from '../stores/quiz';
import AlertPopup from '@/components/AlertPopup.vue';

const quizStore = useQuizStore();

const quiz = ref('');
const selectedType = ref('');
const message = ref('');
const alertType = ref('');
const quizTypes = ref([]);

onMounted(async () => {
    quizTypes.value = await quizStore.fetchAllQuizTypes();
});

async function createQuiz() {
    if (quiz.value === '' || selectedType.value === '') {
        message.value = "Veuillez remplir le nom et le type du quiz";
        alertType.value = 'error';
        return;
    }
    const [status, data] = await quizStore.createQuiz(quiz.value, selectedType.value);
    if (status !== 201) {
        message.value = "Une erreur est survenue lors de la création du quiz";
        alertType.value = 'error';
    } else {
        quiz.value = '';
        selectedType.value = '';
        message.value = `Le quiz "${data.quiz}" a bien été créé"`;
        alertType.value = 'success';
    }
}
</script>