<template>
    <div v-if="!isLoading">
        <ErrorMessage :status="status" />
    </div>
    <div v-show="!isLoading" class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Tous les quiz
        </div>
        <div>
            <RouterLink to="/create-quiz"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Créer un nouveau quiz
            </RouterLink>
        </div>
    </div>
    <div v-show="!isLoading"
        class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-2">#</th>
                                    <th scope="col" class="px-6 py-2">Quiz</th>
                                    <th scope="col" class="px-6 py-2">Questions</th>
                                    <th scope="col" class="px-6 py-2">Créateur</th>
                                    <th scope="col" class="px-6 py-2">Difficulté</th>
                                    <th scope="col" class="px-6 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="quiz in quizzes"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ quiz.id }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        {{ quiz.name }}
                                        <div class="flex items-center mt-1">
                                            <div class="text-xs text-white mr-2 bg-gray-700 rounded-lg px-1 py-1 items-center"
                                                v-for="keyword in quiz.keywords">
                                                {{ keyword }}
                                            </div>
                                        </div>

                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ quiz.questions }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ quiz.owner.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium"><DifficultyShower :difficultyLevel="quiz.difficulty"/></td>
                                    <td class="whitespace-nowrap px-6 py-4 flex"><svg xmlns="http://www.w3.org/2000/svg" @click="openModal(quiz.name, quiz.id)"
                                            v-tooltip="'Créer une activité à partir de ce quiz'" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-10 h-10 hover:cursor-pointer">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <RouterLink to="/update-quiz" v-tooltip="'Modifier un quiz'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-9 h-9 hover:cursor-pointer ml-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </RouterLink>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ActivityModal v-model:isOpen="showModal" :quizId="selectedQuizId" :quizName="selectedQuizName" />
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useQuizStore } from '../stores/quiz';
import DifficultyShower from '@/components/DifficultyShower.vue';
import ActivityModal from '../components/ActivityModal.vue';
import ErrorMessage from '@/components/StatusError.vue';

const quizStore = useQuizStore();
const quizzes = computed(() => quizStore.allQuizzes);
const isLoading = ref(true);
const status = ref(0);

const showModal = ref(false);
const selectedQuizId = ref(null);
const selectedQuizName = ref('');

const openModal = (quizName, quizId) => {
    selectedQuizId.value = quizId;
    selectedQuizName.value = quizName;
    showModal.value = true;
};

onMounted(async () => {
    const status = await quizStore.fetchAllQuizzes();
    if (status === 200) {
        isLoading.value = false;
    }
});

</script>