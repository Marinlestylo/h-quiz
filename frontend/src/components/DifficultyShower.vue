<template>
    <div v-tooltip="'Difficulté : ' + difficulties[difficultyLevel]">
        <img :src="url" :alt="difficulties[difficultyLevel]" class="bg-gray-700 py-2 rounded-full" />
    </div>
</template>

<script setup>
const props = defineProps({
    difficultyLevel: Number
});
const difficulties = ['easy', 'medium', 'hard', 'insane'];
const url = new URL(`/src/assets/images/${difficulties[props.difficultyLevel]}.svg`, import.meta.url);

</script>