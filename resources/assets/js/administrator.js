/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Event = new Vue();

//Vue.component('user-list',require('./components/userList.vue'));
//Vue.component('user-edit',require('./components/userEdit.vue'));

Vue.component('user-list',require('./components/userList.vue'));


const app = new Vue({
    el: '#app',
    data: {
        title: 'List Users',
        users: [],
        games: [] ,
    },
    methods: {
        getUsers: function(){
            axios.get('api/users')
                .then(response=>{this.users = response.data.data;});
        },
        getGames: function(){
            axios.get('api/games')
                .then(response=>{this.games = response.data.data;});
        }
    },
    mounted() {
        this.getUsers();
        this.getGames();
    }
});
