import { ref } from 'vue'
import { defineStore } from 'pinia'

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useCourseStore = defineStore('course', () => {
  const allCourses = ref(null)
  const fetchApi = (uri, settings) => {
    const url = new URL(uri, backUrl);
    return fetch(url, {...settings, credentials: 'include'}).then((response) => {
      if (response.status === 401) {
        user.value = null;
      }
      return response;
    })
  }

  const fetchAllCourses = async () => {
    const response = await fetchApi('/api/courses');
    const data = await response.json();
    allStudents.value = data;
    return response.status;
  }

  return { allCourses, fetchAllCourses}
});
