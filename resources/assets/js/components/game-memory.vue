<template>
    <div class="gameseparator">
        <div>
            <h2 class="text-center">Game: {{title}}</h2>
            <br>
        </div>
        <div class="game-zone-content">
            <div class="alert" :class="alerttype">
                <strong v-if="game.gameEnded">{{ message }} &nbsp;&nbsp;&nbsp;&nbsp;<a v-on:click.prevent="closeGame">Close Game</a></strong>
            </div>
            <div class="board">
                <div v-for="(piece, key) of game.board">
                    <img v-bind:src="pieceImageURL(piece)" v-on:click="clickPiece(key)">
                </div>
            </div>
            <hr>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['game'],
        data: function(){
            return {
                alerttype : "",
            }
        },
        computed: {
            message: function(){
                // return Message to show
                return "Winner Winner Chicken Dinner !" + ((this.game.winner == 1) ? this.game.player1.name : this.game.player2.name);
            },
            title: function(){
                return this.game.player1.name + ((this.game.player2 != null) ? " vs " +this.game.player2.name : "");
            }
        },
        methods: {
            closeGame (){
                // Click to close game
            },
            clickPiece(index){
                this.$socket.emit('play_piece',
                    {gameID: this.game.gameID,
                        piecePos: index});
            },
            pieceImageURL: function (piece) {
                var imgSrc = String(piece);
                return 'img/' + imgSrc + '.png';
            },
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