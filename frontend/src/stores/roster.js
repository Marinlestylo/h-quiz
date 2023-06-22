import { ref } from 'vue'
import { defineStore } from 'pinia'

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useRosterStore = defineStore('rosters', () => {
    const rosters = ref(null)
    const fetchApi = (uri, settings) => {
        const url = new URL(uri, backUrl);
        return fetch(url, { ...settings, credentials: 'include' }).then((response) => {
            if (response.status === 401 || response.status === 403) {
                rosters.value = null;
            }
            return response;
        })
    }

    const fetchRosters = async () => {
        const response = await fetchApi('/api/rosters');
        const data = await response.json();
        rosters.value = data.data;
        return response.status;
    }

    const fetchOneRoster = async (id) => {
        const response = await fetchApi(`/api/rosters/${id}`);
        const data = await response.json();
        // return data, response.status;
        return data;
    }

    const fetchStudentsFromRoster = async (id) => {
        const response = await fetchApi(`/api/rosters/${id}/students`);
        const data = await response.json();
        return data;
    }

    const deleteStudentFromRoster = async (id, studentId) => {
        const payload = {
            'roster_id': id,
            'student_id': studentId
        };
        const url = new URL('/api/rosters/delete-student', backUrl);
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
            credentials: 'include'
        });
        const data = await response.json();
        return [response.status, data];
    }

    const addStudentToRoster = async (id, studentId) => {
        const payload = {
            'roster_id': id,
            'student_id': studentId
        };
        const url = new URL('/api/rosters/add-student', backUrl);
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
            credentials: 'include'
        });
        const data = await response.json();
        return [response.status, data];
    }

    const createRoster = async (payload) => {
        const url = new URL('/api/rosters', backUrl);
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload),
            credentials: 'include'
        });
        const data = await response.json();
        return [response.status, data];
    }

    
        

    return { rosters, createRoster, addStudentToRoster, deleteStudentFromRoster, fetchStudentsFromRoster, fetchOneRoster, fetchRosters }
});
