<template>
    <div class="text-3xl min-w-max mr-64 mb-6">
        Bienvue sur le mode Drill.
    </div>
    <div class="flex mb-6 max-w-3xl w-full">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input v-model="searchKeyword"
                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                list="students" name="student" id="student" placeholder="Entrez un mot-clé" autocomplete="off">
            <datalist id="students" v-if="keywords !== null">
                <option v-for="keyword in keywords" :key="keyword.id">{{ keyword.name }}</option>
            </datalist>
            <button type="submit" @click="chose"
                class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Choisir ce mot-clé
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useKeywordStore } from '../stores/keyword';
import router from '../router';

const keywordStore = useKeywordStore();
const keywords = computed(() => keywordStore.allKeywords);
const searchKeyword = ref('');

onMounted(async () => {
    await keywordStore.fetchAllKeywords();
});

const chose = () => {
    router.push('/drill/' + searchKeyword.value)
    searchKeyword.value = '';
}
</script>