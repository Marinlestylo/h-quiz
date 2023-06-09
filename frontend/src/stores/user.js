import { ref } from 'vue'
import { defineStore } from 'pinia'

export const backUrl = import.meta.env.VITE_BACKEND_URL;
export const appUrl = import.meta.env.VITE_APP_URL;


export const useUserStore = defineStore('user', () => {
  const user = ref(null)
  const fetchApi = (uri, settings) => {
    const url = new URL(uri, backUrl);
    return fetch(url, {...settings, credentials: 'include'}).then((response) => {
      if (response.status === 401) {
        user.value = null;
      }
      return response;
    })
  }

  const fetchUser = async () => {
    const response = await fetchApi('/api/user');
    if (response.status === 200) {
      const data = await response.json();
      user.value = data;
      return data;
    }
  }

  const logout = async () => {
    user.value = null;
    const response = await fetchApi('/api/logout');
  }
  return { user, fetchUser, logout }
});
