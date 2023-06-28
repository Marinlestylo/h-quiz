import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useActivityStore = defineStore('activity', () => {
    const allActivities = ref(null)

    // Pas encore testé
    const fetchAllActivities = async () => {
        const response = await utils.fetchApi('/api/activities');
        const data = await response.json();
        if (response.status === 401 || response.status === 403) {
            allActivities.value = null;
            return;
        }
        allActivities.value = data.data;
        return response.status;
    }

    const createActivity = async (duration, quizId, rosterId, shuffleQuestions, shufflePropositions) => {
        const payload = {
            'duration': duration * 60, //On passe en secondes
            'quiz_id': quizId,
            'roster_id': rosterId,
            'shuffle_questions': shuffleQuestions,
            'shuffle_propositions': shufflePropositions
        };
        const response = await utils.fetchApi('/api/activities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    const updateActivity = async (action) => {
        const payload = {
            'action': action
        };
        const response = await utils.fetchApi('/api/activities/' + activityId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    const deleteActivity = async (activityId) => {
        const response = await utils.fetchApi('/api/activities/' + activityId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        return [response.status, data];
        // return response.status;
    }



    return { allActivities, updateActivity, deleteActivity, createActivity, fetchAllActivities }
});