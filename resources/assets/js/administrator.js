/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Event = new Vue();

/*Vue.component('user-list',require('./components/userList.vue'));
Vue.component('game-list',require('./components/gameList.vue'));*/

Vue.component('admin',require('./components/administrator.vue'));

const app = new Vue({
    el: '#app',
    data: {
    },
    methods: {

    },
    mounted() {
    }
});
