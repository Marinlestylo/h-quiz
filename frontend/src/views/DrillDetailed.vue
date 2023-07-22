<template>
    <div class="text-2xl mb-4">
        Drill sur les questions de type "{{ keyword }}"
    </div>

    <div v-if="loading"></div>
    <div v-else>
        <div v-if="noQuestion">
            Il n'y aucune question pour ce mot-clé.
        </div>
        <div class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-3xl mb-4" v-else>
            <div>
                <div v-if="drill[0].question.type === 'short-answer'">
                    <ShortAnswer :content="drill[0].question.content" v-model:shortAnswer="answer" />
                </div>
                <div v-else-if="drill[0].question.type === 'multiple-choice'">
                    <MultipleChoice :content="drill[0].question.content" v-model:selected="choices" />
                </div>
                <div v-else-if="drill[0].question.type === 'fill-in-the-gaps'">
                    <FillGaps :content="drill[0].question.content" :option="drill[0].question.options"
                        v-model:selected="choices" />
                </div>
                <div v-else-if="drill[0].question.type === 'code'">
                    <CodeQuestion :content="drill[0].question.content" v-model:code="answer" />
                </div>
                <div v-else-if="drill[0].question.type === 'long-answer'">
                    <LongAnswer :content="drill[0].question.content" v-model:answer="answer" />
                </div>
                <div v-else>
                    Ce type de question n'est pas encore supporté.
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="text-2xl my-3">
            Timer :
            <span>{{minutes}}</span>:<span>{{ seconds }}</span>
        </div>
        <button @click="submitAnswer" v-if="!showAnswer"
            class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Répondre
        </button>
    </div>
    <div v-if="showAnswer" class="text-lg">
        Votre réponse est :
        <span v-if="drill[0].question.type === 'fill-in-the-gaps' || drill[0].question.type === 'multiple-choice'">
            {{ choices }}
            <div>
                La bonne réponse est : {{ drill[0].question.validation }}
            </div>
        </span>
        <span v-else>
            {{ answer }}
            <div class="max-w-3xl">
                La bonne réponse est : {{ drill[0].question.validation.expected }}
            </div>
        </span>
        <div>
            Votre réponse a été comptabilisée comme : {{ isCorrect ? 'correcte' : 'incorrecte' }}
        </div>
        <button @click="nextQuestion"
            class="text-white bg-gray-700 my-4 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Question
            suivante
        </button>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useDrillStore } from '../stores/drill';
import ShortAnswer from '@/components/questions/ShortAnswer.vue';
import MultipleChoice from '@/components/questions/MultipleChoice.vue';
import FillGaps from '@/components/questions/FillGaps.vue';
import CodeQuestion from '@/components/questions/CodeQuestion.vue';
import LongAnswer from '@/components/questions/LongAnswer.vue';
import { useStopwatch } from 'vue-timer-hook'

const autoStart = true;
const stopwatch = useStopwatch(autoStart);
const minutes = computed(() => stopwatch.minutes.value < 10 ? `0${stopwatch.minutes.value}`: stopwatch.minutes.value);
const seconds = computed(() => stopwatch.seconds.value < 10 ? `0${stopwatch.seconds.value}`: stopwatch.seconds.value);

const drillStore = useDrillStore();
const drill = computed(() => drillStore.currentDrill);

const noQuestion = ref(false);
const loading = ref(true);

const route = useRoute();
const keyword = computed(() => route.params.keyword);

const choices = ref([]);
const answer = ref('');
const showAnswer = ref(false);

const isCorrect = ref(false);

onMounted(async () => {
    await drillStore.fetchCurrentDrill(keyword.value);
    if (drill.value.length === 0) {
        noQuestion.value = true;
    }
    drill.value.sort((a, b) => (a.next_repetition > b.next_repetition) ? 1 : -1);
    loading.value = false;
});

const submitAnswer = async () => {
    stopwatch.pause();
    const time = stopwatch.minutes.value * 60 + stopwatch.seconds.value;
    let data;
    if (drill.value[0].question.type === 'fill-in-the-gaps' || drill.value[0].question.type === 'multiple-choice') {
        data = await drillStore.submitAnswer(drill.value[0].question.id, choices.value, drill.value[0].id, time);
    } else {
        data = await drillStore.submitAnswer(drill.value[0].question.id, answer.value, drill.value[0].id, time);
    }
    isCorrect.value = data.correct;
    showAnswer.value = true;
    drill.value[0] = data.drill.data;
}

const nextQuestion = async () => {
    drill.value.sort((a, b) => (a.next_repetition > b.next_repetition) ? 1 : -1);
    showAnswer.value = false;
    choices.value = [];
    answer.value = '';
    isCorrect.value = false;
    stopwatch.reset();
}

</script>