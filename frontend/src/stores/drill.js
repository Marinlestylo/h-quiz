import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useDrillStore = defineStore('drill', () => {
    const currentDrill = ref({});

    const fetchCurrentDrill = async (keyword) => {
        const response = await utils.fetchApi(`/api/drills/${keyword}`);
        const data = await response.json();
        if (response.status !== 200) {
            currentDrill.value = {};
            return;
        }
        currentDrill.value = data.data;
        return response.status;
    }

    const submitAnswer = async (questionId, answer, drillId, timeInSec) => {
        const payload = {
            'time': timeInSec,
            'question_id': questionId,
            'answer': answer,
            'drill_id': drillId
        }
        const response = await utils.fetchApi(`/api/drills/answer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return data;
    }
    
    return { currentDrill, fetchCurrentDrill, submitAnswer }
});