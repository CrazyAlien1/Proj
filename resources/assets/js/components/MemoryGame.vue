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

                <button class="btn btn-xs btn-success" @click.prevent=clickLogin>Login</button>
                <button class="btn btn-xs btn-success" @click.prevent=showRegister>Register</button>

                <span v-if="loginError">
                    <h4 class="text-danger">{{ loginError }}</h4>
                </span>
            </span>

        <div class="jumbotron" v-if="showRegisterDiv">
            <h2>Register</h2>
            <div class="form-group">
                <label for="inputName">Name</label>
                <input
                        type="text" class="form-control" v-model="newUser.name"
                        name="name" id="inputName"
                        placeholder="Fullname" required/>
            </div>

            <div class="form-group">
                <label for="inputUserName">User Name</label>
                <input
                        type="text" class="form-control" v-model="newUser.username"
                        name="name" id="inputUserName"
                        placeholder="User Name" required/>
            </div>

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                        type="email" class="form-control" v-model="newUser.email"
                        name="email" id="inputEmail"
                        placeholder="Email address" required/>
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input
                        type="password" class="form-control" v-model="newUser.password"
                        name="name" id="inputPassword"
                        placeholder="Password" required/>
            </div>

            <div class="form-group">
                <a class="btn btn-default" v-on:click.prevent="saveUser">Register</a>
                <a class="btn btn-default" v-on:click.prevent="cancelRegister">Cancel</a>
            </div>
        </div>

            <h3 class="text-center">
                <span v-if="isConnected" class="text-success">Online</span>
                <span v-if="!isConnected" class="text-danger">Offline</span>

                Mrs Derp:
                <span v-if="logedIn" >{{ currentUser.email }}</span>
                {{ title }}
            </h3>

            <div class="row">
                <button class="btn btn-primary" @click.prevent="showLobby = !showLobby">Lobby</button>
                <br>
            </div>


            <div class="row">
                <select v-model="selectedGameType">
                    <option v-for="(type, key) in gameType" :key="key" selected="type">{{type}}</option>
                </select>
                <select v-model="selectedNumPlayers">
                    <option v-for="(max, key) in maxPlayers" :key="key" selected="max">{{max}}</option>
                </select>
                <input v-model="rows">
                <input v-model="cols">
                <input v-model="gameName" placeholder="3 Musketeers">
                <p><button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button></p>
                <hr>
            </div>
            <div class="row" >

                <div v-bind:class="lobbySize" v-show="showLobby">
                    <h3 class="text-center">Lobby</h3>

                    <h4>Pending games (<a @click.prevent="loadLobby">Refresh</a>)</h4>

                    <lobby :games="lobbyGames" @join-click="join"
                                                @start-game="start"
                                                @delete-click="deleteGame"
                                                @remove-click="removePlayer"
                                                @leave-click="leaveGame"
                                                @send-click="sendMessage">
                    </lobby>
                </div>
            </div>
            <div class="row">
                <h1>GAMES!</h1>
                <template v-for="game in myGames">
                    <game :game="game" :user="user" @close_game="closeGame"  @send-click="sendMessage"></game>
                </template>
            </div>

    </div>
</template>

<script type="text/javascript">
    import Lobby from './lobby-games.vue';
    import GameMemory from './game-memory.vue';

    export default {
        data: function(){
            return {
                user : undefined,
                lobbySize:{
                    "col-12" : true,
                },
                lobbyGames: [],
                myGames: [],
                chatChannels: [],
                socketId: "",
                showLogin: false,
                showLobby : true,
                gameType : ['singleplayer', 'multiplayer'],
                selectedGameType : 'singleplayer',
                maxPlayers : [ 2, 3, 4 ],
                selectedNumPlayers : 2,
                rows : 2,
                cols : 2,
                gameName : '',
                isConnected : false,
                images: [],
                userID : undefined,
                currentUser: {email: '', password: '' },
                logedIn: false,
                tokenType: '',
                userToken: '',
                loginError: '',
                token: '',
                showRegisterDiv : false,
                newUser:{name:'',username:'',email: '', password: '' },
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
                console.log("SERVER ASKED TO AUTHENTICATE");
                  this.joinServer();
            },
            disconnect(){
                console.log('socket disconnected');
                this.socketId = "";
                this.isConnected = false;
            },
            player_disconnected(resp){
                console.log("Player Diconnect: " + resp.player);


                this.updateGame(this.lobbyGames, resp.game);
                let outcome = this.updateGame(this.myGames, resp.game);
            },
            lobby_changed(){
                // For this to work, websocket server must emit a message
                this.loadLobby();
            },
            lobby_updated(data){
                this.updateLobby(data);
            },
            game_started(resp){
                console.log("Game Started");
                //let outcome = this.updateGame(this.myGames, resp);

                if(resp.type == 'multiplayer'){
                    this.startGameTimer(resp);
                }

                resp.chatMessages = [];

                this.myGames.push(resp);
                console.log(this.myGames);
            },
            game_refresh(resp){
                console.log("GAME_REFRESH");

                this.softUpdate(this.myGames, resp);

            },
            game_ended(resp){
                console.log("GAME_ENDED");
                let game = this.getGame(this.myGames, resp.id);
                if(game.interval !== undefined){
                    clearInterval(game.interval);
                }
                this.clearGameTimer(game);
                this.updateGame(this.myGames, resp);
                //Nao deixar o user clicar mais nele...
            },
            game_switch_turn(resp){
                console.log("~~~~SWITCHING  PLAYER:~~~~", resp);
                let game = this.getGame(this.myGames, resp.id);
                this.clearGameTimer(game);

                if(resp.type == 'multiplayer') {
                    this.startGameTimer(resp);
                }

                console.log("GAME BEFORE SWITCH", game);

                this.updateGame(this.myGames, resp);
            },
            game_kick(resp){
                alert(resp);
            },
            invalid_play(resp){
                alert(resp);
            },
            login_failed(error){
              console.log(error);
            },
            create_game_error(error){
                this.nodeError(error);
            },
            got_message(resp){
                console.log("MESSAGE!");
                console.log(resp);
                let game = this.getGame(this.myGames, resp.game);
                if(game !== undefined && game.chatMessages !== undefined){
                    game.chatMessages.push(resp.msg);
                }else{
                    console.log("Game is undefined");
                }
            },
        },
        methods: {
            clearGameTimer(game){
                if(game.interval !== undefined){
                    clearInterval(game.interval);
                }
            },
            startGameTimer(game){
                game.timer = game.timer / 1000;
                console.log("TIMER: "+ game.timer);
                this.clearGameTimer(game);
                game.interval = setInterval(function () {
                    game.timer --;
                    //console.log("INTERVAL: "+ game.counterTimer);
                }, 1000);
            },
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
                this.lobbyGames = response;
            },
            updateGame(arr, updatedGame){
                let gameId = arr.findIndex((ele)=> {
                    return ele.id === updatedGame.id
                });

                if(gameId === -1){
                    return false;
                }else if(arr[gameId].status == 'active'){
                    let keepChat = arr[gameId].chatMessages;
                    console.log("KEEP CHAT...", keepChat.chatMessages);

                    Vue.set(arr, gameId, updatedGame);

                    arr[gameId].chatMessages = keepChat;
                    return true;
                }
            },
            softUpdate(arr, updatedGame){

                let game = this.getGame(this.myGames, updatedGame.id);

                //Se for o msm jogador da reset ao counter mas e se a jogada for 1 peca tb faz reset ao counter...
                if(updatedGame.newTurn && updatedGame.type == 'multiplayer'){
                    console.log("RESTART TIMER");
                    game.timer = updatedGame.timer;
                    this.startGameTimer(game);
                }
                for(let i = 0; i < game.players.length; i++){
                    game.players[i].Points = updatedGame.players[i].Points;
                }

                game.message = updatedGame.message;
                game.board = updatedGame.board;
                game.playerTurn = updatedGame.playerTurn;
            },
            getGame(arr, id){
                let gameId = arr.findIndex((ele)=> {
                    return ele.id === id
                });
                return arr[gameId];
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
                                                        gameMaxPlayers: this.selectedNumPlayers,
                                                        rows : this.rows,
                                                        cols : this.cols
                                                    });
                }
            },
            closeGame(gameID){
                let gameId = this.myGames.findIndex((ele)=> {
                    return ele.id === gameID
                });

                if(gameId > -1){
                    Vue.delete(this.myGames, gameId);
                }
            },
            deleteGame(gameID){
                console.log(gameID);
                this.$socket.emit('delete_game', {gameId : gameID});
            },
            removePlayer(data){
                this.$socket.emit('remove_player_game', {gameId: data.gameID, userID: data.playerID});
            },
            leaveGame(gameID){
                this.$socket.emit('leave_game', {gameId: gameID});
            },
            join(game){
                // Click to join game
                this.$socket.emit('request_join_game', { gameId: game.id});
            },
            start(game){
                //console.log("#####################");
                //console.log(game);
                //console.log("#####################");
                this.$socket.emit('start_game', {gameId: game.id});
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
            },
            sendMessage(data){
                console.log("Sending message", data);
                this.$socket.emit('send_message', data);
            }
        },
        showRegister(){
            this.showRegisterDiv = true;
        },
        saveUser(){
            axios.post('api/user',
                {"name" : this.newUser.name},
                {"username" : this.newUser.username},
                {"email" : this.newUser.email },
                {"password" : this.newUser.password })
                .then(response=>{
                    console.log("New User created");

                })
                .catch(error=>{
                    console.log(error);

                });
        },
        cancelRegister(){
            this.showRegisterDiv = false;
        },
        computed: {
            title() {
                return ' Memory Game ' + (this.user !== undefined ? this.user.nickname : '');
            },
        },
        components: {
            'lobby': Lobby,
            'game': GameMemory,
        },
        mounted() {
            this.userID = prompt("Enter user ID to fake login", "1");

            this.loadLobby();
            this.joinServer();
            //Send node the username and password you want to login

        }

    }
</script>

<style>

</style>
