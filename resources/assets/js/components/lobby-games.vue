<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Player 1</th>
            <th>Board Size</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="game in games"  :key="game.gameID">
            <td>{{ game.name }}</td>
            <td>{{ game.owner }}</td>
            <td>{{game.numPieces}}</td>
            <td>
                <span v-if="game.isFull" class="alert alert-danger">Game is full!</span>
                <a v-if="!game.isFull && !isOwner(game.owner)" class="btn btn-xs btn-primary" v-on:click.prevent="join(game)">Join</a>
                <a  class="btn btn-xs btn-danger" v-on:click.prevent="deleteGame(game.ID)">X</a>
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
            deleteGame(game){
                this.$emit('delete-click', game);
            },
            removePlayer(user){
                this.$emit('remove-click', game);
            },
            isOwner(owner){
                if(this.$parent.user.nickname !== undefined){
                    return owner == this.$parent.user.nickname;
                }
            },

        },
    }
</script>

<style scoped>

</style>
