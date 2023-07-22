<template>
    <BacktickText :content="content" />
    <textarea type="text" rows="10" class="border-2 border-gray-300 rounded-md p-2 w-full mt-2" :value="answer"
        @input="$emit('update:answer', $event.target.value)">
        </textarea>
</template>

<script setup>
import BacktickText from './BacktickText.vue';

const props = defineProps({
    content: {
        type: String,
        required: true,
    },
    answer: {
        type: String,
        required: true,
    },
});
</script>