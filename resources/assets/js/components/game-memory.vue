<template>
    <div class="gameseparator">
        <div>
            <h2 class="text-center">Game: {{title}}</h2>
            <br>
        </div>
        <div class="game-zone-content">
            <div class="alert" :class="alerttype">
                <div v-if="game.message.length" v-for="msg in game.message">>
                    <strong>{{msg}}</strong>
                </div>
                <strong v-if="game.gameEnded">{{ message }} &nbsp;&nbsp;&nbsp;&nbsp;<a v-on:click.prevent="closeGame">Close Game</a></strong>
                <strong v-if="!game.gameEnded">{{game.timer}}s remaining</strong>
            </div>
            <div class="row">

                <div class="col-xs-12 col-sm-5" v-if="game.type == 'multiplayer'">
                    <button class="btn btn-primary" v-on:click.prevent="showChat = !showChat">{{loginToogleButton}}</button>
                        <webchat v-if="showChat" :messages="game.chatMessages" @send-click="sendMessage"></webchat>
                </div>
                <div class="col-xs-12  col-sm-5 board">
                    <div v-for="(pieceID, key) of game.board">
                        <img v-bind:src="pieceImageURL(pieceID)" v-on:click="clickPiece(key)">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                    <ul>
                        <li v-for="(player, key) in game.players">
                            <p>{{player.name}} -> {{player.Points}}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
    </div>
</template>

<script type="text/javascript">
    import WebChat from './web-chat.vue';
    const HIDDEN = './storage/images/hidden.png';
    const REVEALED = './storage/images/empty.png';

    export default {
        props: ['game', 'user'],
        data: function(){
            return {
                alerttype : "",
                showChat : true,
            }
        },
        methods: {
            closeGame (){
                this.$emit('close_game', this.game.id);
            },
            clickPiece(index){
                if(this.game.gameEnded){

                    return;
                }
                this.$socket.emit('play_piece',
                    {gameId: this.game.id,
                        pieceIndex: index});
            },
            pieceImageURL: function (pieceID) {
                if(pieceID === -1){
                    return HIDDEN;
                }else if(pieceID === -2){
                    return REVEALED;
                }
                    else{
                    return './storage/images/'+ pieceID+ '.png';
                }
            },
            isPlayerTurn(){
                return this.game.playerTurn.ID === this.$parent.user.id;
            },
            sendMessage(data){
                data.gameId = this.game.id;
                this.$emit('send-click', data);
            }
        },
        computed: {
            message: function(){

                if(this.$parent.user === undefined)
                    return '';
                return "Winner Winner Chicken Dinner!  " + this.game.winner.name + " "+ this.game.winner.Points;
            },
            title: function(){
                if(this.$parent.user === undefined)
                    return '';
                return this.game.name;
            },
            loginToogleButton : function(){
                if(this.showChat){
                    return 'Collapse Chat';
                }else{
                    return 'Show Chat';
                }
            }
        },
        components: {
            'webchat': WebChat,
        }
    }
</script>

<style scoped>
    .gameseparator{
        border-style: solid;
        border-width: 2px 0 0 0;
        border-color: black;
    }
</style>