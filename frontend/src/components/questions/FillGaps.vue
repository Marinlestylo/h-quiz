<template>
    <div v-if="option.gaps">
        <span v-for="(content, index) in separatedContent">
        <span>
            {{ content }}
        </span>
        <span v-if="option.gaps[index]">
            <select v-model="selected[index]" id="answer"
                class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1 mb-1">
                <option v-for="gap in option.gaps[index]" :key="index">{{ gap }}</option>
            </select>
        </span>
    </span>
    </div>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({
    content: {
        type: String,
        required: true,
    },
    option: {
        type: Object,
        required: true,
    },
    selected: {
        type: Array,
        required: true,
    },
});

const separatedContent = computed(() => {
    const splitted = props.content;
    return splitted.split('*-*');
});
</script>