<template>
    <div class="row">
            <h3 class="text-center">{{ title }}</h3>

            <div class="row">
                <button class="btn btn-primary" @click.prevent="showChat = !showChat">WebChat</button>
                <button class="btn btn-primary" @click.prevent="showLobby = !showLobby">Lobby</button>
                <br>
            </div>


            <div class="row" >
                <div class="col-12 col-md-3" v-show="showChat">
                    Put this on the left side floating and able to collapse
                    <webchat></webchat>
                </div>

                <div v-bind:class="lobbySize" v-show="showLobby">
                    <h3 class="text-center">Lobby</h3>
                    <select v-model="selectedGameType">
                        <option v-for="type in gameType">{{type}}</option>
                    </select>
                    <input v-model="rows">
                    <input v-model="cols">
                    <input v-model="gameName" placeholder="3 Musketeers">
                    <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button></p>
                    <hr>

                    <h4>Pending games (<a @click.prevent="loadLobby">Refresh</a>)</h4>

                    <lobby :games="lobbyGames" @join-click="join" @delete-click="deleteGame"></lobby>
                </div>
            </div>
            <div class="row">
                <h1>GAMES!</h1>
                <template v-for="game in myGames">
                    <game :game="game" @play_piece="play"></game>
                </template>
            </div>

    </div>
</template>

<script type="text/javascript">
    import WebChat from './web-chat.vue';
    import Lobby from './lobby-games.vue';
    import GameMemory from './game-memory.vue';

    export default {
        data: function(){
            return {
                user : undefined,
                lobbySize:{
                    "col-12" : true,
                    "col-md-9" : this.showChat,
                },
                lobbyGames: [],
                myGames: [],
                chatChannels: [],
                socketId: "",
                showChat: false,
                showLobby : true,
                gameType : ['singleplayer', 'multiplayer'],
                selectedGameType : undefined,
                rows : 2,
                cols : 2,
                gameName : '',
                isConnected : false,
            }
        },
        sockets:{
            connect(){
                console.log('socket connected');
                this.socketId = this.$socket.id;
            },
            success_join_server(){
                //The Node server now has his name on it's list
                this.isConnected = true;
            },
            request_authenticate(){
                  this.joinServer();
            },
            disconnect(){
                console.log('socket disconnected');
                this.socketId = "";
                this.isConnected = false;
            },
            player_disconnected(resp){
                let gameId = this.activeGames.findIndex((ele)=> {
                    return ele.gameID == resp.gameID
                });
                let player = resp.playerName;
                if(gameId >= 0 || gameId < this.activeGames.lenght)
                {
                    this.activeGames.splice(gameId, 1);
                    alert("Player "+ player +" left the game");
                }
            },
            lobby_changed(){
                // For this to work, websocket server must emit a message
                this.loadLobby();
            },
            lobby_updated(data){
                console.log("Got Lobby from Server");
                this.updateLobby(data);
            },
            game_refresh(resp){
                console.log("GAME_REFRESH");
                let gameId = this.activeGames.findIndex((ele)=> {
                    return ele.gameID == resp.gameID
                });

                if(gameId === -1){
                    this.activeGames.push(resp);
                }else{
                    Vue.set(this.activeGames, gameId, resp);
                    //this.activeGames[gameId] = resp;
                }
            },
            create_game_error(error){
                this.nodeError(error);
            }
        },
        methods: {
            joinServer(){
                console.log("Joining Server...");
                if(this.user !== undefined){
                    this.$socket.emit('authenticate_server', {userName: this.user.nickname});
                }
            },
            loadLobby(){
                /// send message to server to load the list of games on the lobby
                this.$socket.emit('get_lobby');

            },
            loadActiveGames(){
                /// send message to server to load the list of games that player is playing
                this.$socket.emit('get_my_active_games');
            },
            updateLobby(response){
                console.log(response);
                this.lobbyGames = response;
            },
            createGame(){
                // For this to work, server must handle (on event) the "create_game" message
                if(this.user !== undefined){
                    //Pedir ao Laravel para criar
                    axios.post('/api/games',{
                        //falta o csrf token!
                        userId : this.user.id,
                        type : this.selectedGameType
                    }).then(response=> {
                        console.log('Game Created!\n Sending Node the news!');
                        console.log(response.data.gameID);
                        this.$socket.emit('create_game', {  gameID: response.data.gameID,
                                                            playerName: this.user.nickname,
                                                            gameName: this.gameName,
                                                            rows : this.rows,
                                                            cols : this.cols });
                    }).catch(error=>{
                        console.log(error.response);
                        alert('[WARNING] '+ error.response.data.errors.type);
                    });

                }
            },
            deleteGame(gameID){
                axios.delete('api/games/'+gameID)
                .then(response => {
                    console.log('Game successfully deleted');
                    this.$socket.emit('delete_game', gameID);
                });
            },
            join(game){
                // Click to join game
                console.log("#####################");
                console.log(game);
                console.log("#####################");
                this.$socket.emit('request_join_game', { gameID: game.gameID, playerName: this.currentPlayer });
            },
            play(game){
                // play a game - click on piece on specified index
                //refresh the game...
                this.loadActiveGames();
            },
            close(game){
                // to close a game
            },
            nodeError(error){
                alert('[NODE]: '+ error);
            },
        },
        computed: {
            title() {
                var connectedSpan = `<span class="alert alert-success"> Multiplayer</span>`;
                var disconnectedSpan = '<span class="alert alert-warning"> Offline</span>';;
                var status = (this.isConnected) ? connectedSpan : disconnectedSpan;
                return status + ' Memory Game Welcome '+ (this.user !== undefined ? this.user.nickname : '');
            },
        },
        components: {
            'webchat' : WebChat,
            'lobby': Lobby,
            'game': GameMemory,
        },

        mounted() {
            var userID = prompt("Please enter your name", "Harry Potter");
            axios.get('api/users/'+ userID)
                .then(response => {
                    this.user = response.data.data;
                    this.loadLobby();
                    //Agora que ja tem o username ja pode enviar ao node o pedido de authenticate_server
                    this.joinServer();
                });
        }

    }
</script>

<style>

</style>