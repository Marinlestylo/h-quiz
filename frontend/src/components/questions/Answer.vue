<template>
    <div class="flex">
        <div class="block mb-2 text-lg font-medium text-gray-900 w-48 mr-2">
            Réponse
        </div>

        <div class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-4xl text-sm">
            <div v-if="questionType === 'short-answer'" class="w-96">
                <div class="mb-2 flex justify-between items-center" v-tooltip="'Ce champ est optionnel'">
                    <label for="regex">Regex :</label>
                    <input type="text" name="regex" id="regex" class="ml-2 border-gray-500 border rounded lg p-1 w-60"
                        v-model="answer.pattern">
                </div>
                <div class="flex justify-between items-center">
                    <label for="exact">Valeur exacte : </label>
                    <input type="text" name="exact" id="exact" class="ml-2 border-gray-500 border rounded lg p-1 w-60"
                        v-model="answer.expected">
                </div>
            </div>
            <div v-else-if="questionType === 'multiple-choice'">
                <div class="mb-2 w-96">
                    <div class="mb-2">
                        <label for="regex">Liste de bonnes réponses : {{ answer }}</label>
                    </div>
                    <div class="flex justify-between items-center">
                        <input type="text" name="regex" id="regex" class="border-gray-500 border rounded lg p-1 w-36"
                            v-model="newValue" placeholder="1,3,4" autocomplete="off">
                        <button @click="addAnswer"
                            class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex">Remplacer les
                            réponses</button>
                    </div>
                </div>
            </div>
            <div v-else-if="questionType === 'fill-in-the-gaps'" class="max-w-3xl">
                <div class="mb-2">
                    Réponses : {{ answer }}
                </div>
                <FillGaps :content="content" :option="option" v-model:selected="choices"/>
                <button @click="changeAnswer" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex mt-2">Changer les réponses</button>
            </div>
            <div v-else-if="questionType === 'code'" class="w-96">
                <div class="flex justify-between items-center">
                    <label for="exact">Output attendu : </label>
                    <input type="text" name="exact" id="exact" class="ml-2 border-gray-500 border rounded lg p-1 w-60"
                        v-model="answer.expected">
                </div>
            </div>
            <div v-else>
                Ce type de question n'est pas encore supporté.
            </div>
        </div>
    </div>
    <button @click="debug">WWWW</button>
</template>

<script setup>
import { computed, ref } from 'vue';
import FillGaps from './FillGaps.vue';
const props = defineProps({
    questionType: {
        type: String,
        required: true,
    },
    answer: {
        type: [Object, String],
        required: true,
    },
    option:{
        type: Object,
        required: false,
    },
    content: {
        type: String,
        required: false,
    },
});

const emit = defineEmits(['update:answer']);
const newValue = ref('');
const addAnswer = () => {
    if (newValue.value === '') {
        return;
    }
    let temp = newValue.value.split(',');
    temp = temp.map((element) => {
        return parseInt(element);
    });
    if (temp.some(isNaN)) {
        return;
    }
    newValue.value = '';
    emit('update:answer', temp);
}

const choices = ref([]);

const changeAnswer = () => {
    emit('update:answer', choices.value);
}

const debug = () => {
    console.log(choices.value);
}
</script>