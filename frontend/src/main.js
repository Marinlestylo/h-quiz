import { createApp } from 'vue'
import { createPinia } from 'pinia'
import FloatingVue from 'floating-vue'
import VueCountdown from '@chenfengyuan/vue-countdown';

import App from './App.vue'
import router from './router'
import 'floating-vue/dist/style.css'

import './assets/main.css'

const pinia = createPinia();
const app = createApp(App);

app.use(pinia);
app.use(router);
app.component(VueCountdown.name, VueCountdown);

app.use(FloatingVue);

app.mount('#app');