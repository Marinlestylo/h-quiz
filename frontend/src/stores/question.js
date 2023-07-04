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

    const fetchQuestionById = async (id) => {
        const response = await utils.fetchApi(`/api/questions/${id}`);
        return await response.json();
    }

    const fetchAllQuestionTypes = async () => {
        const response = await utils.fetchApi('/api/questions-types');
        return await response.json();
    }

    const fetchAllDifficultyLevels = async () => {
        const response = await utils.fetchApi('/api/questions-difficulties');
        return await response.json();
    }

    const createQuestion = async (question) => {
        const payload = question;
        const response = await utils.fetchApi('/api/questions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    return { allQuestions, createQuestion, fetchAllDifficultyLevels, fetchAllQuestionTypes, fetchQuestionById, fetchAllQuestions }
});