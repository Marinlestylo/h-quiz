import { ref } from 'vue'
import { defineStore } from 'pinia'

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useQuizStore = defineStore('quiz', () => {
    const allQuizzes = ref(null)
    const fetchApi = (uri, settings) => {
        const url = new URL(uri, backUrl);
        return fetch(url, { ...settings, credentials: 'include' }).then((response) => {
            if (response.status === 401) {
                user.value = null;
            }
            return response;
        })
    }

    const createQuiz = async (name) => {
        const payload = { 'name': name };
        const response = await fetchApi('/api/quizzes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    return { allQuizzes, createQuiz }
});