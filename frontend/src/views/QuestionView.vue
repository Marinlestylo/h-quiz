<template>
    <div class="mb-6 flex justify-between items-center">
        <div class="text-3xl">
            Toute les questions
        </div>
        <div>
            <RouterLink to="/create-question"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Créer une nouvelle question
            </RouterLink>
        </div>
    </div>

    <!-- List of questions in quiz -->
    <div v-if="questions === null" class="text-xl">Il n'y actuellement aucune question.</div>
    <div v-else class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-2 py-2">#</th>
                                    <th scope="col" class="px-2 py-2">Nom</th>
                                    <th scope="col" class="px-2 py-2">Contenu</th>
                                    <th scope="col" class="px-6 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="question in questions"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="px-2 py-4 font-medium max-w-xs">{{ question.id }}</td>
                                    <td class="px-2 py-4 font-medium w-40">{{ question.name }}</td>
                                    <td class="px-2 text-xs py-4 font-medium max-w-lg overflow-x-auto">{{ question.content
                                    }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        <RouterLink :to="`/update-question/${question.id}`" v-tooltip="'Modifier cette question'">
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
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useQuestionStore } from '../stores/question';
import { RouterLink } from 'vue-router';

const questionStore = useQuestionStore();
const questions = computed(() => questionStore.allQuestions);

onMounted(async () => {
    if (questions.value === null) {
        await questionStore.fetchAllQuestions();
    }
});

</script>