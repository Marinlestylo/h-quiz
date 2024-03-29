import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useActivityStore = defineStore('activity', () => {
    const allActivities = ref(null)
    const currentlyUsedActivity = ref({
        activity: {},
        questions: [],
        answers: [],
    })

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

    const fetchConnectedStudentActivities = async () => {
        const response = await utils.fetchApi('/api/user/activities');
        const data = await response.json();
        return [response.status, data];
    }

    const fetchOneActivity = async (activityId) => {
        const response = await utils.fetchApi('/api/activities/' + activityId);
        const data = await response.json();
        if (response.status === 200) {
            currentlyUsedActivity.value.activity = data.data;
        }
        return response.status;
    }

    const fetchActivityQuestion = async (activityId, question) => {
        const response = await utils.fetchApi('/api/activities/' + activityId + '/questions/'+question);
        const data = await response.json();
        if (response.status === 200) {
            currentlyUsedActivity.value.questions[question-1] = data.data;
            currentlyUsedActivity.value.answers[question-1] = data.data.answered;
        }
        return response.status;
    }

    const addOneAnswer = (idQuestion, data) => {
        currentlyUsedActivity.value.answers[idQuestion-1] = data;
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

    const updateActivity = async (action, id) => {
        const payload = {
            'action': action
        };
        const response = await utils.fetchApi('/api/activities/' + id, {
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
    }

    const compileCode = async (code) => {
        const payload = {
            'code': code
        };
        const response = await utils.fetchApi('/api/test-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        return [response.status, data];
    }

    const submitQuizAnswer = async (activityId, questionNuber, answer) => {
        const payload = {
            'answer': answer
        };
        const response = await utils.fetchApi('/api/activities/' + activityId + '/questions/' + questionNuber, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
    }

    const studentFinishExam = async (activityId) => {
        const payload = {
            'activity_id': activityId
        };
        const response = await utils.fetchApi('/api/activities/' + activityId + '/finish', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        if (response.status === 200) {
            currentlyUsedActivity.value.activity = {};
        }
    }

    const activityResultsQuestions = async (activityId) => {
        const response = await utils.fetchApi('/api/activities/' + activityId + '/questions');
        const data = await response.json();
        return [response.status, data];
    }
    
    return { allActivities, currentlyUsedActivity, updateActivity, deleteActivity, createActivity, fetchAllActivities, fetchConnectedStudentActivities, fetchOneActivity, fetchActivityQuestion, addOneAnswer, compileCode, submitQuizAnswer, studentFinishExam, activityResultsQuestions }
});