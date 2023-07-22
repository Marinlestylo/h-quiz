import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';

export const useRosterStore = defineStore('rosters', () => {
    const rosters = ref(null)

    const fetchRosters = async () => {
        if (rosters.value !== null) {
            return rosters.value;
        }

        const response = await utils.fetchApi('/api/rosters');
        if (response.status === 200) {
            const data = await response.json();
            rosters.value = data.data;
            return response.status;
        }
    }

    const fetchOneRoster = async (id) => {
        const response = await utils.fetchApi(`/api/rosters/${id}`);
        const data = await response.json();
        return data;
    }

    const fetchStudentsFromRoster = async (id) => {
        const response = await utils.fetchApi(`/api/rosters/${id}/students`);
        const data = await response.json();
        return data;
    }

    const deleteStudentFromRoster = async (id, studentId) => {
        const payload = {
            'roster_id': id,
            'student_id': studentId
        };
        const response = await utils.fetchApi('/api/rosters/student', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();
        return [response.status, data];
    }

    const addStudentToRoster = async (id, studentId) => {
        const payload = {
            'roster_id': id,
            'student_id': studentId
        };
        const response = await utils.fetchApi('/api/rosters/student', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();
        return [response.status, data];
    }

    const createRoster = async (payload) => {
        const response = await utils.fetchApi('/api/rosters', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();
        return [response.status, data];
    }

    
        

    return { rosters, createRoster, addStudentToRoster, deleteStudentFromRoster, fetchStudentsFromRoster, fetchOneRoster, fetchRosters }
});
