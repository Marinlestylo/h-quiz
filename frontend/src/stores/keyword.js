import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useKeywordStore = defineStore('keyword', () => {
    const allKeywords = ref(null)

    const fetchAllKeywords = async () => {
        const response = await utils.fetchApi('/api/keywords');
        const data = await response.json();
        if (response.status === 401 || response.status === 403) {
            allKeywords.value = null;
            return;
        }
        allKeywords.value = data.keywords;
        return response.status;
    }

    return { allKeywords, fetchAllKeywords }
});