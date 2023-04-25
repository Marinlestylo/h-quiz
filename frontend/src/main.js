import { createApp } from 'vue'
import { createPinia } from 'pinia'
import authentication from './plugins/authentication'

import App from './App.vue'
import router from './router'

import './assets/main.css'
const renderApp = () => {
    const app = createApp(App)

    app.use(createPinia())
    app.use(router)
    app.mount('#app')
};

authentication.CallLogin(renderApp);