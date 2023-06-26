import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useQuestionStore = defineStore('question', () => {
    const allQuestions = ref(null)

    // TODO : changer endpoint
    const fetchAllQuestions = async () => {
        const response = await utils.fetchApi('/api/questions/all');
        const data = await response.json();
        if (response.status === 401 || response.status === 403) {
            allQuestions.value = null;
            return;
        }
        allQuestions.value = data;
        return response.status;
    }

    return { allQuestions, fetchAllQuestions }
});