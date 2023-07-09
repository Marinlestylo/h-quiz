<template>
    <BacktickText :content="content" />
    <input
        type="text"
        class="border-2 border-gray-300 rounded-md p-2 w-full mt-2"
        :value="shortAnswer"
        @input="$emit('update:shortAnswer', $event.target.value)"
    />
</template>

<script setup>
import BacktickText from './BacktickText.vue';

const props = defineProps({
    content: {
        type: String,
        required: true,
    },
    shortAnswer: {
        type: String,
        required: true,
    },
});
</script>