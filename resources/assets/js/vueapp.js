/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueSocketio from 'vue-socket.io';

Vue.use(VueRouter);

Vue.use(VueSocketio, 'http://127.0.0.1:8080');

const memoryGame = Vue.component('memorygame', require('./components/MemoryGame.vue'));
const administration = Vue.component('administrator', require('./components/administrator.vue'));

const routes = [
    { path: '/', name: 'home', component: memoryGame },
    { path: '/memorygame', component: memoryGame },
    { path: '/administration', name: 'administration', component: administration }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    router,
    data:{

    },
    methods: {

    },
    mounted() {
    }
}).$mount('#app');
