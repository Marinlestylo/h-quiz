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
        <div v-else-if="questionType === 'code'">
            <CodeQuestion :content="content" v-model:code="answer"/>
        </div>
        <div v-else-if="questionType === 'long-answer'">
            <LongAnswer :content="content" v-model:answer="answer"/>
        </div>
        <div v-else>
            Ce type de question n'est pas encore supporté.
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ShortAnswer from '@/components/questions/ShortAnswer.vue';
import MultipleChoice from '@/components/questions/MultipleChoice.vue';
import FillGaps from '@/components/questions/FillGaps.vue';
import CodeQuestion from '@/components/questions/CodeQuestion.vue';
import LongAnswer from '@/components/questions/LongAnswer.vue';

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
        required: false,
    }
});

const shortAnswer = ref('');
const choices = ref([]);
const answer = ref('');
</script>