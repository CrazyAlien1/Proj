<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Game ID</th>
            <th>Type</th>
            <th>Status</th>
            <th>Total Players</th>
            <th>Owner</th>
            <th>Winner</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="game in games"  :key="game.id">
            <td>{{ game.id }}</td>
            <td>{{ game.type }}</td>
            <td>{{ game.status }}</td>
            <td>{{ game.total_players }}</td>
            <td>{{ game.created_by }}</td>
            <td>{{ game.winner }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script type="text/javascript">
    // Component code (not registered)
    module.exports={
        props:["games"],
        data: function () {
            return {
            }
        },
        methods:{

        }
    }
</script>

<style scoped>
    /*	  Specific style applied only on the component*/
</style>