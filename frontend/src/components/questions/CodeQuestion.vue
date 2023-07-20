<template>
    <BacktickText :content="content" />
    <textarea type="text" rows="10" class="border-2 border-gray-300 rounded-md p-2 w-full mt-2" :value="code"
        @input="$emit('update:code', $event.target.value)">
        </textarea>

    <button @click="compile"
        class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Compiler
        le code
    </button>
    <div v-show="output" class="mt-4">
        Output : {{ output }}
    </div>
</template>

<script setup>
import { ref } from 'vue';
import BacktickText from './BacktickText.vue';
import { useActivityStore } from '../../stores/activity';

const store = useActivityStore();
const output = ref('');

const props = defineProps({
    content: {
        type: String,
        required: true,
    },
    code: {
        type: String,
        required: true,
    },
});

const compile = async () => {
    if (!props.code) {
        return;
    }
    const [status, data] = await store.compileCode(props.code);
    if (data.verdict === 'Accepted' || data.verdict === 'Wrong Answer') {
        output.value = data.testCasesResult.defaultTestId.output;
    } else {
        output.value = data.verdict;
    }
    
};
</script>