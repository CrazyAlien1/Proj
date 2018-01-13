/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';

Vue.use(VueRouter);

window.Event = new Vue();

Vue.component('admin',require('./components/administrator.vue'));

const memoryGame = Vue.component('memorygame', require('./components/MemoryGame.vue'));

const routes = [
    { path: '/', name: 'home', component: memoryGame }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router,
    data: {
    },
    methods: {

    },
    mounted() {
    }
}).$mount('#app');
