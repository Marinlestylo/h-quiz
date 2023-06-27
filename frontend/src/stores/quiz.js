import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useQuizStore = defineStore('quiz', () => {
    const allQuizzes = ref(null)

    const fetchAllQuizzes = async () => {
        const response = await utils.fetchApi('/api/quizzes');
        const data = await response.json();
        if (response.status === 401 || response.status === 403) {
            allQuizzes.value = null;
            return;
        }
        allQuizzes.value = data.quizzes;
        return response.status;
    }

    const fetchQuestionsFromQuiz = async (id) => {
        const response = await utils.fetchApi(`/api/quizzes/${id}/questions`);
        const data = await response.json();
        return data;
    }

    const createQuiz = async (name) => {
        const payload = { 'name': name };
        const response = await utils.fetchApi('/api/quizzes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    const addQuestionToQuiz = async (id, questionId) => {
        const payload = {
            'quiz_id': id,
            'question_id': questionId
        };
        const response = await utils.fetchApi('/api/quizzes/question', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    const deleteQuestionFromQuiz = async (id, questionId) => {
        const payload = {
            'quiz_id': id,
            'question_id': questionId
        };
        const response = await utils.fetchApi('/api/quizzes/question', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    return { allQuizzes, addQuestionToQuiz, deleteQuestionFromQuiz, fetchQuestionsFromQuiz, fetchAllQuizzes, createQuiz }
});