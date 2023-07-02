<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Création d'une nouvelle question
        </div>
        <div>
            <RouterLink to="/quizzes" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Retour vers toutes les questions
            </RouterLink>
        </div>
    </div>

    <!-- Création d'une nouvelle question -->
    <div class="w-full">
        <div class="mb-6 flex">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Nom de la question</label>
            <input type="text" id="name" v-model="question.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Boucle for" autocomplete="off">
        </div>

        <div class="mb-6 flex">
            <label for="content" class="block mb-2 text-lg font-medium text-gray-900 w-64">Contenu de la question</label>
            <textarea name="content" id="content" rows="10" v-model="question.content"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 resize-none"
                placeholder="Contenu de la question"></textarea>
        </div>

        <div class="flex mb-6">
            <label for="student" class="block mb-2 text-lg font-medium text-gray-900 w-64">Ajouter un mot-clé</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input v-model="searchKeyword"
                    class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    list="students" name="student" id="student" placeholder="Entrez un mot-clé" autocomplete="off">
                <datalist id="students" v-if="keywords !== null">
                    <option v-for="keyword in keywords" :key="keyword.id">{{ keyword.name }}</option>
                </datalist>
                <button type="submit" @click="addKeywords"
                    class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Ajouter
                    ce mot-clé
                </button>
            </div>
        </div>

        <div class="mb-6 flex">
            <label for="keywords" class="block mb-2 text-lg font-medium text-gray-900 w-48 mr-2">Mot-clés actuel</label>
            <div>
                <div v-if="!question.keywords.length" class="text-xl">Il n'y actuellement aucun mot-clé.</div>
                <div v-else
                    class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4">
                    <div class="flex flex-col">
                        <div class="-mx-8">
                            <div class="inline-block py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="px-2 py-2">Nom</th>
                                                <th scope="col" class="px-10 py-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="keyW in question.keywords"
                                                class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                                <td class="px-2 py-4 font-medium max-w-xs">{{ keyW.name }}</td>
                                                <td class="px-2 py-4 font-medium"><button @click="removeKeywords(keyW.id)"
                                                        class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto">
                                                        Supprimer
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6 flex">
            <label for="difficulty" class="block mb-2 text-lg font-medium text-gray-900 w-48 mr-2">Difficulté</label>
            <div>
                <select v-model="question.difficulty" id="difficulty"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option disabled selected value="">Quelle est la difficulté de la question ?</option>
                    <option v-for="diff in difficulties" :key="diff" v-bind:value="diff">{{ diff }}</option>
                </select>
            </div>
        </div>

        <div class="mb-6 flex">
            <label for="type" class="block mb-2 text-lg font-medium text-gray-900 w-48 mr-2">Type de la question</label>
            <div>
                <select v-model="question.type" id="type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option disabled selected value="">Quelle est le type de la question ?</option>
                    <option v-for="t in questionTypes" :key="t" v-bind:value="t">{{ t }}</option>
                </select>
            </div>
        </div>

        <div class="mb-6 flex">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Explication de la réponse</label>
            <input type="text" id="name" v-model="question.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                autocomplete="off">
        </div>

        <div>
            <button @click="debug"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto mb-4">Création de
                la question
            </button>
        </div>
    </div>
</template>

<script setup>

import { computed, onMounted, ref } from 'vue';
import { useKeywordStore } from '../stores/keyword';
import { useQuestionStore } from '../stores/question';

const keywordStore = useKeywordStore();
const keywords = computed(() => keywordStore.allKeywords);

const questionStore = useQuestionStore();

const searchKeyword = ref('');

const difficulties = ref([]);
const questionTypes = ref([]);

const question = ref({
    name: '',
    content: '',
    keywords: [],
    difficulty: '',
    type: '',
    explanation: '',
});

const debug = () => {
    console.log(question.value);
}

const addKeywords = () => {
    const matchingKeyword = keywords.value.find(keyword => keyword.name === searchKeyword.value);
    if (matchingKeyword) {
        const alreadyAdded = question.value.keywords.find(keyword => keyword.id === matchingKeyword.id);
        if (alreadyAdded) {
            return;
        }
        question.value.keywords.push(matchingKeyword);
        searchKeyword.value = '';
    }
}

const removeKeywords = (id) => {
    const matchingKeyword = question.value.keywords.find(keyword => keyword.id === id);
    if (matchingKeyword) {
        question.value.keywords = question.value.keywords.filter(keyword => keyword.id !== id);
    }
}

onMounted(async () => {
    if (keywords.value === null) {
        await keywordStore.fetchAllKeywords();
    }

    difficulties.value = await questionStore.fetchAllDifficultyLevels();
    questionTypes.value = await questionStore.fetchAllQuestionTypes();

});

</script>