import {createApp} from 'vue';
import {setupRouter} from './router';
import App from './App.vue';
import './styles/app.css';

createApp(App)
    .use(setupRouter())
    .mount('#app');
