<template>
    <div>
        <h1>WebChat!</h1>
        <div class="card-body">
            <div id="chatMsgScrollable" class="web-chat-text">
                <blockquote v-bind:class="chatTextClass(msg.sender)" v-for="msg in messages">
                    <p>
                        {{msg.text}}
                        <small v-if="msg.sender">{{msg.sender}} {{calculateTime(msg.date)}}</small>
                    </p>

                </blockquote>
            </div>
            <div>
                <textarea v-model="msgToSend" v-on:keyup.enter="sendMessage"></textarea>
                <button class="btn btn-primary" @click="sendMessage">Send</button>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code (not registered)
    module.exports={
        props: ['messages'],
        data: function(){
            return {
                msgToSend : undefined,
                chatTextClass : function(sender){
                    console.log(sender, " == ", this.$parent.user.nickname);
                    return (sender != this.$parent.user.nickname) ? "blockquote" : "blockquote blockquote-reverse";
                },
            }
        },
        methods: {
            sendMessage(){
                console.log("SENDING MESSAGE:: "+ this.gameID);

                this.$emit('send-click', {message : this.msgToSend});
                this.msgToSend = '';
            },
            calculateTime(time){
                var now = new Date();
                time = new Date(time);
                var diffDays = DateDiff.DateMeasure(now - time);
                console.log(diffDays.days + " M "+ diffDays.hours);
                if(diffDays.days <= 1){
                    if(diffDays.hours < 1){
                        if(diffDays.minutes < 1){
                            return "Moments Ago.."
                        }
                        return diffDays.minutes+" min";
                    }
                    return time.getHours()+":"+time.getMinutes()+":"+time.getSeconds();
                }
                return time.getDate()+ "-" + (time.getMonth() + 1) + "-" + time.getFullYear();
            },
        },
        watch: {
            'messages': function (val, oldVal) {
                this.$nextTick(function () {
                    var el = document.getElementById("chatMsgScrollable");
                    el.scrollTop = el.scrollHeight;
                });
            }
        }
    }

    var DateDiff = {
        DateMeasure: function(ms) {
            var d, h, m, s;
            s = Math.floor(ms / 1000);
            m = Math.floor(s / 60);
            s = s % 60;
            h = Math.floor(m / 60);
            m = m % 60;
            d = Math.floor(h / 24);
            h = h % 24;

            return {
                days: d,
                hours: h,
                minutes: m,
                seconds: s
            }
        }
    };
</script>

<style scoped>
    .web-chat-text {
        height: 200px;
        overflow: scroll;
    }
</style>