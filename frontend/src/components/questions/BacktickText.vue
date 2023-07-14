<template>
    <div class="flex">
        <div v-for="(element, index) in separatedContent" :key="index">
            <span v-if="!(index % 2)">
                {{ element }}
            </span>
            <span v-else>
                <code class="bg-gray-200 px-2 mx-2 py-1 rounded-md text-red-400">
                    {{ element }}
                </code>
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: {
        type: String,
        required: true,
    }
});

const separatedContent = computed(() => {
    const splitted = props.content;
    return splitted.split('`');
}); 

</script>