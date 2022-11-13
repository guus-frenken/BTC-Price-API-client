import {createApp} from 'vue';
import {setupRouter} from './router';
import {createPinia} from 'pinia';
import App from './App.vue';
import './styles/app.css';

createApp(App)
    .use(setupRouter())
    .use(createPinia())
    .mount('#app');
