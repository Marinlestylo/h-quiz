<template>
    <div>
        <div class="mb-4">
            En cas de besoin, n'hésitez pas à consulter la <RouterLink to="/documentation" target="_blank"
                class="text-blue-500 hover:text-blue-700">documentation</RouterLink> des questions.
        </div>
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

        <!-- Search bar coming from here : https://flowbite.com/docs/forms/search-input/ -->
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
            <label class="mb-2 text-lg font-medium text-gray-900 w-48">Visibilité</label>
            <button class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 ml-2 px-4 rounded mb-4"
                @click="question.public = !question.public">
                {{ question.public ? 'Publique' : 'Privée' }}</button>
        </div>

        <div class="mb-6 flex">
            <label for="points" class="block mb-2 text-lg font-medium text-gray-900 w-48">Nombre de points</label>
            <input type="number" id="points" v-model="question.points"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2.5 ml-2"
                min="0" autocomplete="off">
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

        <div class="mb-6">
            <Answer :questionType="question.type" v-model:answer="question.validation" v-model:option="question.option"
                v-model:nbFillGaps="nbFillGaps" />
        </div>

        <div class="mb-6 flex">
            <label for="explanation" class="block mb-2 text-lg font-medium text-gray-900 w-64">Explication de la
                réponse</label>
            <input type="text" id="explanation" v-model="question.explanation"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                autocomplete="off">
        </div>
        <div>
            <QuestionPreview :questionType="question.type" :content="question.content" :option="question.option" />
        </div>
        <div>
            <AlertPopup v-model:message="message" :alertType="popupType" class="my-4" />
        </div>
        <div>
            <button @click="editQuestion"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto mb-4 mt-4">Sauvegarder
                la question
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useKeywordStore } from '@/stores/keyword';
import { useQuestionStore } from '@/stores/question';
import AlertPopup from '@/components/AlertPopup.vue';
import { RouterLink } from 'vue-router';
import QuestionPreview from '@/components/QuestionPreview.vue';
import Answer from '@/components/questions/Answer.vue';

const questionStore = useQuestionStore();

const keywordStore = useKeywordStore();
const keywords = computed(() => keywordStore.allKeywords);

const searchKeyword = ref('');

const difficulties = ref([]);
const questionTypes = ref([]);
const message = ref('');
const popupType = ref('error');
const nbFillGaps = computed(() => {
    if (props.question.content === '') {
        return 0;
    }
    return props.question.content.split('*-*').length - 1;
});

const props = defineProps({
    question: {
        type: Object,
        required: true
    },
    usage: {
        type: String,
        required: true
    }
})

const editQuestion = async () => {
    if (!validateQuestion()) {
        displayPopup('Veuillez remplir tous les champs correctement.', 'error');
        return;
    }
    const [status, data] = await apiCall();
    if (status === 201 && props.usage === 'create') {
        resetFields();
    }
    displayPopup(data.message, status === 201 || status === 200 ? 'success' : 'error');
}

const apiCall = async () => {
    if (props.usage === 'create') {
        return await questionStore.createQuestion(props.question);
    } else if (props.usage === 'update') {
        return await questionStore.updateQuestion(props.question);
    } else {
        console.log('Mauvais usage du props editQuestion');
        return;
    }
}

const displayPopup = (m, t) => {
    message.value = m;
    popupType.value = t;
}

onMounted(async () => {
    if (keywords.value === null) {
        await keywordStore.fetchAllKeywords();
    }

    difficulties.value = await questionStore.fetchAllDifficultyLevels();
    questionTypes.value = await questionStore.fetchAllQuestionTypes();
});

const addKeywords = () => {
    const matchingKeyword = keywords.value.find(keyword => keyword.name === searchKeyword.value);
    if (matchingKeyword) {
        const alreadyAdded = props.question.keywords.find(keyword => keyword.id === matchingKeyword.id);
        if (!alreadyAdded) {
            props.question.keywords.push(matchingKeyword);
        }
        searchKeyword.value = '';
    }
}

const removeKeywords = (id) => {
    const matchingKeyword = props.question.keywords.find(keyword => keyword.id === id);
    if (matchingKeyword) {
        props.question.keywords = props.question.keywords.filter(keyword => keyword.id !== id);
    }
}

const validateQuestion = () => {
    if (props.question.name === '') {
        return false;
    }
    if (props.question.content === '') {
        return false;
    }
    if (props.question.difficulty === '' || !difficulties.value.includes(props.question.difficulty)) {
        return false;
    }
    if (props.question.type === '' || !questionTypes.value.includes(props.question.type)) {
        return false;
    }
    if (props.question.validation === {}) {
        return false;
    }
    return true;
}

const resetFields = () => {
    props.question.name = '';
    props.question.content = '';
    props.question.difficulty = '';
    props.question.type = '';
    props.question.explanation = '';
    props.question.keywords = [];
    props.question.validation = {};
    props.question.option = {};
    props.question.public = false;
    props.question.points = 0;
}
</script>