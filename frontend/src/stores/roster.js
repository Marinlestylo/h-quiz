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

    return { rosters, fetchStudentsFromRoster, fetchOneRoster, fetchRosters }
});
