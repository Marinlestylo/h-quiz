import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';


export const useStudentStore = defineStore('student', () => {
  const allStudents = ref(null)

  const fetchAllStudents = async () => {
    if (allStudents.value !== null) {
      return allStudents.value;
    }

    const response = await utils.fetchApi('/api/students');
    if (response.status === 200) {
      const data = await response.json();
      allStudents.value = data;
      return response.status;
    }
  }

  return { allStudents, fetchAllStudents }
});
