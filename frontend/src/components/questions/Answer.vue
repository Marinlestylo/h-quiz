<template>
    <div class="flex">
        <div class="block mb-2 text-lg font-medium text-gray-900 w-48 mr-2">
            Réponse
        </div>

        <!-- Short-answer -->
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

            <!-- QCM -->
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


            <!-- Fill gaps -->
            <div v-else-if="questionType === 'fill-in-the-gaps'" class="max-w-3xl">
                <div class="mb-2 w-96">
                    <div v-if="nbFillGaps === 0" class="mb-2">
                        Il n'y aucun espace à remplir dans cette question.
                    </div>
                    <div v-else v-for="index in nbFillGaps" class="mb-6">
                        <label for="" class="mr-2 mb-2">Trou n°{{ index }} : </label>
                        <span v-if="props.option.gaps">
                            {{ props.option.gaps[index-1] }}
                        </span>
                        <input type="text" name="" :id="index" v-model="propositions[index-1]"
                            class="border-gray-500 border rounded lg p-1 w-96">
                    </div>
                    <div>
                        <label for="ans" class="mr-2 mb-2">Réponses :</label>
                        <span> {{ props.answer }}</span>
                        <input type="text" name="ans" id="ans" class="border-gray-500 border rounded lg p-1 w-96 mb-2"
                            v-model="newValue" autocomplete="off">
                        <button @click="addAnswerFillGaps"
                            class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex">Sauvegarder les
                            réponses</button>
                    </div>
                </div>
            </div>

            <!-- Code and long-answer -->
            <div v-else-if="questionType === 'code' || questionType === 'long-answer'" class="w-full">
                <div class="flex justify-between items-center w-96">
                    <label for="exact">Résultat attendu : </label>
                    <textarea name="exact" id="exact" cols="30" rows="10"
                        class="ml-2 border-gray-500 border rounded lg p-1 w-full" v-model="answer.expected"></textarea>
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
const props = defineProps({
    questionType: {
        type: String,
        required: true,
    },
    answer: {
        type: [Object, String],
        required: true,
    },
    option: {
        type: Object,
        required: false,
    },
    nbFillGaps: {
        type: Number,
        required: false,
    },
});

const emit = defineEmits(['update:answer', 'update:option']);
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

const addAnswerFillGaps = () => {
    if (newValue.value === '') {
        return;
    }
    // check that each elem of propositions is not empty
    for (let i = 0; i < props.nbFillGaps; i++) {
        if (propositions.value[i] === '') {
            return;
        }
    }
    const opt = {
        gaps: []
    }
    for (let i = 0; i < props.nbFillGaps; i++) {
        const temp = propositions.value[i].split(',');
        opt.gaps.push(temp);
    }
    let answer = newValue.value.split(',');
    newValue.value = '';
    propositions.value = [];
    emit('update:answer', answer);
    emit('update:option', opt);
}

const choices = ref([]);
const propositions = ref([]);

const changeAnswer = () => {
    emit('update:answer', choices.value);
}

const debug = () => {
    // console.log(choices.value);
    // console.log(nbFillGaps.value);
    console.log(propositions.value);
    console.log(props.option.gaps);
}
</script>