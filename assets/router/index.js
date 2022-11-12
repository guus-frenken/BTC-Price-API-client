import {createRouter, createWebHistory} from 'vue-router';
import Home from '../views/HomeView';

export function setupRouter() {
    const routes = [
        {
            path: '/',
            name: 'Home',
            component: Home,
        },
    ];

    return createRouter({
        history: createWebHistory(),
        routes,
    });
}
