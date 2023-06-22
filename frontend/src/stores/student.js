import { ref } from 'vue'
import { defineStore } from 'pinia'

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useStudentStore = defineStore('student', () => {
  const allStudents = ref(null)
  const fetchApi = (uri, settings) => {
    const url = new URL(uri, backUrl);
    return fetch(url, {...settings, credentials: 'include'}).then((response) => {
      if (response.status === 401) {
        user.value = null;
      }
      return response;
    })
  }

  const fetchAllStudents = async () => {
    const response = await fetchApi('/api/students');
    const data = await response.json();
    allStudents.value = data;
    return response.status;
  }

  return { allStudents, fetchAllStudents}
});
