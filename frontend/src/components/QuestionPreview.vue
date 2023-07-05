<template>
    <div class="block mb-2 text-lg font-medium text-gray-900 w-64">
        Preview
    </div>
    <div class="border p-4 rounded-lg border-solid border-gray-300 bg-gray-50 max-w-4xl">
        <div v-if="questionType === 'short-answer'">
            <ShortAnswer :content="content" v-model:shortAnswer="shortAnswer" />
        </div>
        <div v-else-if="questionType === 'multiple-choice'">
            <MultipleChoice :content="content" v-model:selected="choices" />
        </div>
        <div v-else-if="questionType === 'fill-in-the-gaps'">
            <FillGaps :content="content" :option="option" v-model:selected="choices"/>
        </div>
        <div v-else-if="questionType === 'multiple-choice'">

        </div>
        <div v-else>
            Ce type de question n'est pas encore supporté.
        </div>
    </div>
    <button @click="debug">WWWWWW</button>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import ShortAnswer from './questions/ShortAnswer.vue';
import MultipleChoice from './questions/MultipleChoice.vue';
import FillGaps from './questions/FillGaps.vue';
import Code from './questions/Code.vue';

const props = defineProps({
    questionType: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        required: true,
    },
    option:{
        type: Object,
        required: true,
    }
});

const shortAnswer = ref('');

const choices = ref([]);

const debug = () => {
    console.log(choices.value);
};
</script>