import { ref } from 'vue'
import { defineStore } from 'pinia'
import * as utils from '../utils.js';

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useUserStore = defineStore('user', () => {
  const user = ref({
    id: null,
    name: '',
    role: '',
  })
  const allUsers = ref(null)

  const fetchUser = async () => {
    if (user.value.id !== null) {
      return user.value;
    }

    const response = await utils.fetchApi('/api/user');
    if (response.status === 200) {
      const data = await response.json();
      user.value.id = data.id;
      user.value.name = data.name;
      user.value.role = data.role;
      // console.log(user.value);
      return data;
    }
  }

  const logout = async () => {
    user.value.id = null;
    user.value.name = '';
    user.value.role = '';
    const response = await fetchApi('/api/logout');
  }

  const fetchAllUsers = async () => {
    const response = await fetchApi('/api/users');
    const data = await response.json();
    allUsers.value = data;
    return response.status;
  }

  return { user, allUsers, fetchAllUsers, fetchUser, logout }
});
