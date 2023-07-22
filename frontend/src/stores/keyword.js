import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useKeywordStore = defineStore('keyword', () => {
    const allKeywords = ref(null)

    const fetchAllKeywords = async () => {
        if (allKeywords.value !== null) {
            return allKeywords.value;
        }

        const response = await utils.fetchApi('/api/keywords');
        if (response.status === 200) {
          const data = await response.json();
          allKeywords.value = data.keywords;
          return response.status;
        }
    }

    return { allKeywords, fetchAllKeywords }
});