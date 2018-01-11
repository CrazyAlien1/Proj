<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Room</th>
            <th>Owner</th>
            <th>Players</th>
            <th>Board Size</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="game in games"  :key="game.id">
            <td>{{ game.id }}</td>
            <td>{{ game.name }}</td>
            <td>{{ game.owner.name }}</td>
            <td>
                <div class="row" v-for="player in game.players">
                    <div class="col col-xs-4">
                        {{player.name}}
                    </div>
                    <div class="col col-xs-2">
                        <button v-if="game.owner.name != player.name" class="btn btn-danger btn-xs" v-on:click.prevent="removePlayer(game.id, player.ID)">X</button>
                    </div>
                </div>
            </td>
            <td>{{game.cols * game.rows}}</td>
            <td>
                <span v-if="game.isFull" class="alert alert-danger">Game is full!</span>

                <div v-if="!isOwner(game.owner.name)">
                    <a v-if="!game.isFull && !isInGame(game)" class="btn btn-xs btn-primary" v-on:click.prevent="join(game)">Join</a>
                    <a v-if="isInGame(game)" class="btn btn-xs btn-danger" v-on:click.prevent="leaveGame(game.ID)">X</a>
                </div>

                <div v-if="isOwner(game.owner.name)">
                    <a v-if="isOwner(game.owner.name)" class="btn btn-xs btn-success" v-on:click.prevent="start(game)">Start</a>
                    <a v-if="isOwner(game.owner.name)" class="btn btn-xs btn-danger" v-on:click.prevent="deleteGame(game.ID)">X</a>
                </div>
            </td>
        </tr>
        </tbody>
        <!--v-if="isOwner(game.owner)"-->
    </table>
</template>

<script type="text/javascript">
    // Component code (not registered)
    module.exports={
        props: ['games'],
        methods: {
            join(game) {
                console.log("Request Join");
                this.$emit('join-click', game);
            },
            start(game) {
                console.log("Request Start");
                this.$emit('start-game', game);
            },
            deleteGame(gameID){
                this.$emit('delete-click', gameID);
            },
            leaveGame(game){
                this.$emit('leave-click', game);
            },
            removePlayer(gameID, playerID){
                this.$emit('remove-click', {gameID, playerID});
            },
            isOwner(owner){
                if(this.$parent.user.nickname !== undefined){
                    return owner === this.$parent.user.nickname;
                }
            },
            isInGame(game){
                for(var i = 0; i < game.players.length; i++) {
                    if (game.players[i].ID === this.$parent.user.id) {
                        return true;
                    }
                }
                return false;
            }
        },
    }
</script>

<style scoped>

</style>