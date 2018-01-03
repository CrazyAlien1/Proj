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
                <div v-for="(pieceID, key) of game.board">
                    <img v-bind:src="pieceImageURL(pieceID)" v-on:click="clickPiece(key)">
                </div>
            </div>
            <hr>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['game', 'playerCurrGames', 'images'],
        data: function(){
            return {
                alerttype : "",

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
            pieceImageURL: function (pieceID) {
                //http://localhost:8000/storage/images/0.png
                let image = this.images.find(ele=> ele.id === pieceID);
                return './storage/images/'+ image.path;
            },
        },
        computed: {
            message: function(){

                if(this.$parent.user === undefined)
                    return '';
                return "Winner Winner Chicken Dinner !" + this.game.winner.nickname;
            },
            title: function(){
                if(this.$parent.user === undefined)
                    return '';
                console.log(this.game);
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