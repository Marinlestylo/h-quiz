<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Modification d'un quiz
        </div>
        <div>
            <RouterLink to="/quizzes" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-64">
                Retour vers tous les quiz
            </RouterLink>
        </div>
    </div>

    <!-- Quiz selection -->
    <div>
        <select v-model="selectedQuiz" @change="displayQuizInfo" id="id_quiz"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option disabled selected value="">Choisissez un quiz</option>
            <option v-for="quiz in quizzes" :key="quiz.id" v-bind:value="quiz.id">{{ quiz.name }}</option>
        </select>
    </div>




    <div v-show="showDetails" class="mt-10">
        <!-- Add questions to quiz -->
        <div class="flex items-center my-6 justify-between font-medium">
            <div class="text-xl">
                Ajouter une question existante au quiz
            </div>
        </div>

        <div class="w-full">

            <div class="mb-6 flex items-center">
                <label for="course" class="block mb-2 text-lg font-medium text-gray-900 w-64">Rechercher une
                    question</label>
                <input type="text" id="question" list="questions" v-model="currentQuestion"
                    class="ml-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                    autocomplete="off">
                <datalist id="questions" v-if="filteredQuestions !== null">
                    <option v-for="question in filteredQuestions" :key="question.id" >{{ question.id + ". " + question.name }}</option>
                </datalist>
            </div>
            <div class="mb-6 flex items-center">
                <label for="course" class="block mb-2 text-lg font-medium text-gray-900 w-64">Filtrer par mot clé</label>
                <input type="text" id="keyword" list="keywords" v-model="currentKeyword"
                    class="ml-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                    placeholder="Conversion binaire" autocomplete="off">
                <datalist id="keywords" v-if="keywords !== null">
                    <option v-for="keyword in keywords" :key="keyword.id">{{ keyword.name }}</option>
                </datalist>
            </div>
            <div class="mb-6 flex items-center">
                <label for="content" class="block mb-2 text-lg font-medium text-gray-900 w-64">Contenu de la question</label>
                <!-- <input type="text-area" id="keyword" list="keywords" v-model="currentKeyword"
                    class="ml-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                    placeholder="Conversion binaire" autocomplete="off"> -->
                <textarea name="content" id="content" cols="20" rows="10" class="ml-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 resize-none" readonly disabled placeholder="Contenu de la question">{{ currentQuestionContent }}</textarea>
            </div>

            <button @click="addQuestionToQuiz"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto">Ajouter cette
                question au quiz
            </button>
        </div>

        <!-- Alert popup -->
        <div>
            <AlertPopup v-model:message="errorMessage" alertType="error" class="my-4" />
            <AlertPopup v-model:message="successMessage" alertType="success" class="my-4" />
        </div>


        <!-- List of questions in quiz -->
        <div v-if="showDetails && !quizDetails.count" class="text-xl">Il n'y actuellement aucune question dans
            ce quiz.</div>
        <div v-else class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-left text-sm font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                    <tr>
                                        <th scope="col" class="px-6 py-2">Nom</th>
                                        <th scope="col" class="px-6 py-2">Contenu</th>
                                        <th scope="col" class="px-6 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="question in quizDetails.questions"
                                        class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                        <td class="px-6 py-4 font-medium max-w-xs">{{ question.name }}</td>
                                        <td class="text-xs py-4 font-medium max-w-sm overflow-x-auto">{{ question.content }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 font-medium"><button
                                                @click="deleteQuestionFromQuiz(question.id)"
                                                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Supprimer</button>
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
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useQuizStore } from '../stores/quiz';
import { useKeywordStore } from '../stores/keyword';
import { useQuestionStore } from '../stores/question';
import AlertPopup from '../components/AlertPopup.vue';

const quizStore = useQuizStore();
const quizzes = computed(() => quizStore.allQuizzes);

const keywordStore = useKeywordStore();
const keywords = computed(() => keywordStore.allKeywords);

const questionStore = useQuestionStore();
const questions = computed(() => questionStore.allQuestions);

const selectedQuiz = ref('');
const quizDetails = ref([]);
const showDetails = ref(false);
const currentKeyword = ref('');
const currentQuestion = ref('');


const filteredQuestions = computed(() => {
    if (currentKeyword.value === '') {
        return questions.value;
    }

    return questions.value.filter(question => {
        return question.keywords.some(keyword => keyword.name.includes(currentKeyword.value));
    });
});

const currentQuestionContent = computed(() => {
    const id = getQuestionId();
    if (id === null) {
        return '';
    }
    return questions.value.find(question => question.id === id).content;
});

const errorMessage = ref('');
const successMessage = ref('');

onMounted(async () => {
    await quizStore.fetchAllQuizzes();
});

async function displayQuizInfo() {
    quizDetails.value = await quizStore.fetchQuestionsFromQuiz(selectedQuiz.value);
    await keywordStore.fetchAllKeywords();
    await questionStore.fetchAllQuestions();
    showDetails.value = true;
    resetFields();
}

async function deleteQuestionFromQuiz(questionId) {
    const [status, data] = await quizStore.deleteQuestionFromQuiz(selectedQuiz.value, questionId);
    if (status === 200) {
        successMessage.value = 'La question a bien été supprimée du quiz.';
        quizDetails.value.questions = data.questions;
    } else {
        errorMessage.value = data.message;
    }
}

const getQuestionId = () => {
    if (currentQuestion.value === '') {
        return null;
    }
    const id = +currentQuestion.value.split('.')[0];
    if (id === undefined || !Number.isInteger(id)) {
        return null;
    }
    return id;
}

const resetFields = () => {
    currentQuestion.value = '';
    currentKeyword.value = '';
    errorMessage.value = '';
    successMessage.value = '';
}

async function addQuestionToQuiz() {
    const id = getQuestionId();
    if (id === null) {
        errorMessage.value = 'Veuillez entrer une question valide.';
        return;
    }
    const [status, data] = await quizStore.addQuestionToQuiz(selectedQuiz.value, id);
    if (status === 200) {
        resetFields();
        successMessage.value = 'La question a bien été ajoutée au quiz.';
        quizDetails.value.questions = data.questions;
    } else {
        errorMessage.value = data.message;
        successMessage.value = '';
    }
}

</script>