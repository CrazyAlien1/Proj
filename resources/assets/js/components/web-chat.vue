<template>
    <div>
        <h1>WebChat!</h1>
        <div class="card col-xs-12 col-sm-8" >
            <div class="card-body">
                <blockquote class="blockquote" v-for="msg in messages">
                    <p v-bind:class="chatTextClass(msg.sender)">
                        {{msg.text}}
                        <small>{{msg.sender}} {{calculateTime(msg.date)}}</small>
                    </p>
                </blockquote>
                <div>
                    <textarea v-model="msgToSend"></textarea>
                    <button class="btn btn-primary" @click="sendMessage">Send</button>
                </div>
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
                    return (sender == this.$parent.$parent.user.name) ? "text-right" : "text-left";
                },
            }
        },
        methods: {
            sendMessage(messages){
                console.log("SENDING MESSAGE:: "+ this.gameID);

                this.$emit('send-click', {message : this.msgToSend});
                messages.msgToSend = '';
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
            }
        },
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

</style>