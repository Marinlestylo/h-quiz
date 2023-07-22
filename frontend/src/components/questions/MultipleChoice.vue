<template>
    <div>
        <BacktickText :content="question" />
    </div>
    <div>
        <div v-for="(choice, index) in choices" :key="index" class="mt-4 flex items-center">
            <button class="border rounded-full border-gray-400 px-2 hover:bg-green-400" @click="selectAnswer(index+1)"
                :class="{ 'bg-green-500': selected.includes(index+1) }">
                {{ index + 1 }}
            </button>
            <span class="ml-2">
                {{ choice }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import BacktickText from '@/components/questions/BacktickText.vue';
const props = defineProps({
    content: {
        type: String,
        required: true,
    },
    selected: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update:selected']);

const splitted = props.content.split('## ');

const question = computed(() => {
    return splitted[0];
});
const choices = computed(() => {
    let choice = splitted.slice(1);
    choice = choice.map((choice) => choice.replace(/\n/g, ''));
    choice = choice.map((choice) => choice.slice(1));
    return choice;
});

const selectAnswer = (index) => {
    const newSelected = [...props.selected];
    if (newSelected.includes(index)) {
        const indexToRemove = newSelected.indexOf(index);
        newSelected.splice(indexToRemove, 1);
        emit('update:selected', newSelected);
        return;
    } else {
        newSelected.push(index);
        emit('update:selected', newSelected);
    }
};
</script>