/**
 * Created by Pedro on 04/11/2017.
 */
console.log("chat")
Vue.component('chatDashboard',
    {
        template: '#chat-Container'
    });


var chatContainer = new Vue({
    el: '#chatContainer',
    data: {
        showChat: false
    },
    methods: {
        toggleChat: function(){
            this.showChat = !this.showChat;
        }
    }
});