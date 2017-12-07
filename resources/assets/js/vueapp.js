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

const routes = [
    { path: '/', component: memoryGame },
    { path: '/memorygame', component: memoryGame }
];

const router = new VueRouter({
    routes:routes
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
