import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';

export const useCourseStore = defineStore('course', () => {
  const allCourses = ref(null)

  const fetchAllCourses = async () => {
    if (allCourses.value !== null) {
      return allCourses.value;
    }

    const response = await utils.fetchApi('/api/courses');
    if (response.status === 200) {
      const data = await response.json();
      allCourses.value = data;
      return response.status;
    }
  }

  return { allCourses, fetchAllCourses }
});
