<template>
    <div class="row">
            <button v-if="logedIn" class="btn btn-primary" @click.prevent="admin">Administration</button>
            <button v-if="logedIn" class="btn btn-primary btn-danger" @click.prevent=clickLogout>Logout</button>
            <button v-if="!logedIn" class="btn btn-primary btn-success" @click.prevent="showLogin = !showLogin">Log me</button>
            <br></br>
            <span v-if="showLogin && !logedIn">
                <label for="currentUser.email">E-Mail Address</label>
                <input v-model="currentUser.email" type="email" class="form-control" id="currentUser.email" required autofocus>

                <label for="currentUser.password">Password</label>
                <input v-model="currentUser.password" type="password" class="form-control" id="currentUser.password" required autofocus>

                <button class="btn btn-xs btn-success" @click.prevent=clickLogin > Login</button>

                <span v-if="loginError">
                    <h4 class="text-danger">{{ loginError }}</h4>
                </span>
            </span>

            <h3 class="text-center">
                <span v-if="isConnected" class="text-success">Online</span>
                <span v-if="!isConnected" class="text-danger">Offline</span>

                Mrs Derp:
                <span v-if="logedIn" >{{ currentUser.email }}</span>
                {{ title }}
            </h3>

            <div class="row">
                <button v-if="logedIn" class="btn btn-primary" @click.prevent="showChat = !showChat">WebChat</button>
                <button class="btn btn-primary" @click.prevent="showLobby = !showLobby">Lobby</button>
                <br>
            </div>


            <div class="row">
                <select v-model="selectedGameType">
                    <option v-for="(type, key) in gameType" :key="key" selected="type">{{type}}</option>
                </select>
                <input v-model="rows">
                <input v-model="cols">
                <input v-model="gameName" placeholder="3 Musketeers">
                <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button></p>
                <hr>
            </div>
            <div class="row" >
                <div class="col-12 col-md-3" v-show="showChat">
                    Put this on the left side floating and able to collapse
                    <webchat></webchat>
                </div>

                <div v-bind:class="lobbySize" v-show="showLobby">
                    <h3 class="text-center">Lobby</h3>

                    <h4>Pending games (<a @click.prevent="loadLobby">Refresh</a>)</h4>

                    <lobby :games="lobbyGames" :token="token" @join-click="join" @delete-click="deleteGame"></lobby>
                </div>
            </div>
            <div class="row">
                <h1>GAMES!</h1>
                <template v-for="game in myGames">
                    <game :game="game" :images="images" :playerCurrGames="myGames.length" @play_piece="play"></game>
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
                showLogin: false,
                showChat: false,
                showLobby : true,
                gameType : ['singleplayer', 'multiplayer'],
                selectedGameType : undefined,
                rows : 2,
                cols : 2,
                gameName : '',
                isConnected : false,
                images: [],
                userId : undefined,
                currentUser: {email: '', password: '' },
                logedIn: false,
                tokenType: '',
                userToken: '',
                loginError: '',
                token: '',
            }
        },
        sockets:{
            connect(){
                console.log('socket connected');
                this.socketId = this.$socket.id;
            },
            success_join_server(userData){
                //The Node server now has his name on it's list
                console.log("Server took me back!");
                this.isConnected = true;
                this.user = userData;
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
            login_failed(error){
              console.log(error);
            },
            create_game_error(error){
                this.nodeError(error);
            }
        },
        methods: {
            joinServer(){
                console.log("Joining Server...");
                this.$socket.emit('authenticate_server', {userID: this.userID});
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
                    //Pedir ao Node para criar o Jogo
                    console.log('Asking Node to ask Laravel if everything is OK');
                    this.$socket.emit('create_game', {
                                                        userID : this.userID,
                                                        gameName: this.gameName,
                                                        gameType: this.selectedGameType,
                                                        rows : this.rows,
                                                        cols : this.cols
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

            },
            close(game){
                // to close a game
            },
            nodeError(error){
                console.log(error);
                alert('[NODE]: '+ error);
            },
            clickLogin(){
                this.loginError = '';
                axios.post('api/login', { email: this.currentUser.email, password: this.currentUser.password })
                    .then(response=>{
                        this.tokenType = response.data.token_type;
                        this.userToken = response.data.access_token;

                        this.token = this.tokenType + " " + this.userToken;

                        //console.log(response.data.access_token);  *caso seja necessario para fazer logout
                        console.log("Logged in");
                        this.logedIn = true;

                    })
                    .catch(error=>{
                        console.log(error.response.data.msg);
                        this.loginError = error.response.data.msg;
                    });
            },
            clickLogout(){
                axios.post('api/logout', {},{headers: {'Authorization': this.token}})
                    .then(response=>{
                        console.log(response.data.msg);
                        this.logedIn = false;
                        this.currentUser.email = '';
                        this.currentUser.password = '';
                        this.showLogin = false;
                        this.token = '';
                        this.tokenType = '';
                        this.userToken = '';
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        computed: {
            title() {
                return ' Memory Game ' + (this.user !== undefined ? this.user.nickname : '');
            },
        },
        components: {
            'webchat' : WebChat,
            'lobby': Lobby,
            'game': GameMemory,
        },
        mounted() {
            this.userID = prompt("Enter user ID to fake login", "1");


            this.loadLobby();
            this.joinServer();
            //Send node the username and password you want to login

            /*
            axios.all([axios.get('api/users/'+ this.userID) , axios.get('api/images')])
                .then(axios.spread((respUsers, respImg) => {
                    //USERS
                    this.user = respUsers.data.data;
                    this.loadLobby();
                    //Agora que ja tem o username ja pode enviar ao node o pedido de authenticate_server
                    this.joinServer();

                    //IMAGES http://localhost:8000/storage/images/0.png
                    this.images = respImg.data.data;

                }));
            */

        }

    }
</script>

<style>

</style>
