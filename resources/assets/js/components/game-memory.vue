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
                <strong v-if="!game.game_ended">{{game.timer}}s remaining</strong>
            </div>
            <div class="board">
                <div v-for="(pieceID, key) of game.board">
                    <img v-bind:src="pieceImageURL(pieceID)" v-on:click="clickPiece(key)">
                </div>
            </div>
            <div>
                <ul>
                    <li v-for="(player, key) in game.players">
                        <p>{{player.name}} -> {{player.Points}}</p>
                    </li>
                </ul>
            </div>
            <hr>
        </div>
    </div>
</template>

<script type="text/javascript">
    const HIDDEN = './storage/images/hidden.png';
    const REVEALED = './storage/images/empty.png';

    export default {
        props: ['game'],
        data: function(){
            return {
                alerttype : "",
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
            }
        },
        computed: {
            message: function(){

                if(this.$parent.user === undefined)
                    return '';
                return "Winner Winner Chicken Dinner !" + this.game.winner.name + " "+ this.game.winner.Points;
            },
            title: function(){
                if(this.$parent.user === undefined)
                    return '';
                return this.game.name;
            },
        },
    }
</script>

<style scoped>
    .gameseparator{
        border-style: solid;
        border-width: 2px 0 0 0;
        border-color: black;
    }
</style>