<template>
    <div class="row">
            <router-link :to="{ name: 'administration' }" class="btn btn-primary" v-if="logedIn">Administration</router-link>
            <button v-if="logedIn" class="btn btn-primary btn-danger" @click.prevent="clickLogout">Logout</button>
            <button v-if="!logedIn" class="btn btn-primary btn-success" @click.prevent="showLogin = !showLogin">Log me</button>
            <button class="btn btn-primary btn-success" @click.prevent="getOfflineStats">Offline statistics</button>
            <button v-if="logedIn" class="btn btn-primary btn-success" @click.prevent="getMyStats">My statistics</button>
            <button v-if="logedIn" class="btn btn-primary btn-success" @click.prevent="showProfile">Profile</button>

            <br></br>
            <span v-if="showLogin && !logedIn">
                <label for="currentUser.email">E-Mail Address</label>
                <input v-model="currentUser.email" type="email" class="form-control" id="currentUser.email" required autofocus>

                <label for="currentUser.password">Password</label>
                <input v-model="currentUser.password" type="password" class="form-control" id="currentUser.password" required autofocus>

                <button class="btn btn-xs btn-success" @click.prevent="clickLogin">Login</button>
                <button class="btn btn-xs btn-success" @click.prevent="clickReset">Reset Password</button>
                <button class="btn btn-xs btn-primary" @click.prevent="showRegisterDiv = !showRegisterDiv">Register</button>


                <span v-if="loginError">
                    <h4 class="text-danger">{{ loginError }}</h4>
                </span>
            </span>

        <div class="jumbotron" v-if="showUserProfile">
            <h2>Profile</h2>

            <!-- name -->
            <div class="form-group" v-if="!editingUser">
                <label for="inputName">Name</label>
                <output name="name">{{ authUser.name}} </output>
            </div>
            <div class="form-group" v-if="editingUser">
                <label for="inputName">Name</label>
                <input value="authUser.name" v-model="editUser.name" type="text" class="form-control" id="editUser.name" required autofocus>
            </div>

            <!-- NickName -->
            <div class="form-group" v-if="!editingUser">
                <label for="inputName">Nickname</label>
                <output name="name">{{ authUser.username}} </output>
            </div>
            <div class="form-group" v-if="editingUser">
                <label for="inputName">Nickname</label>
                <input value="authUser.username" v-model="editUser.username" type="text" class="form-control" id="editUser.nickname" required autofocus>
            </div>

            <!-- email -->
            <div class="form-group" v-if="!editingUser">
                <label for="inputName">email</label>
                <output name="name">{{authUser.email }} </output>
            </div>
            <div class="form-group" v-if="editingUser">
                <label for="inputName">email</label>
                <input value="authUser.email" v-model="editUser.email" type="email" class="form-control" id="editUser.email" required autofocus>
            </div>

            <!-- password -->
            <div class="form-group" v-if="editingUser">
                <label for="inputPassword">Password</label>
                <input v-model="editUser.password" type="password" class="form-control" id="editUser.password" required autofocus>
            </div>

            <div class="form-group">
                <a class="btn btn-primary btn-success" v-if="!editingUser" v-on:click.prevent="editUserProfile">Edit profile</a>
                <a class="btn btn-primary btn-danger" v-if="!editingUser" v-on:click.prevent="removeUser">Remove Account</a>
                <a class="btn btn-primary btn-default" v-if="!editingUser" v-on:click.prevent="desactive">Desactive account</a>
                <a class="btn btn-primary btn-default" v-if="!editingUser" v-on:click.prevent="backEdit">Back</a>
                <a class="btn btn-primary btn-default" v-if="editingUser" v-on:click.prevent="cancelEdit">Cancel</a>
                <a class="btn btn-primary btn-default" v-if="editingUser" v-on:click.prevent="saveChanges">Save Changes</a>
                <a class="btn btn-primary btn-default" v-on:click.prevent="resetPassword">ResetPaword</a>
            </div>
        </div>

        <div class="jumbotron" v-if="isAdmin">
            <h2>Reseting Password</h2>
            <div class="form-group">
                <label for="inputName">Current Password</label>
                <input  v-model="resetingUser.currentPassword" type="text" class="form-control" id="userReset.currentPassword" required>
            </div>
            <div class="form-group">
                <label for="inputName">New Password</label>
                <input  v-model="resetingUser.newPassword" type="password" class="form-control" id="userReset.newPassword" required>
            </div>
            <div class="form-group">
                <label for="inputName">Confirm Password</label>
                <input  v-model="resetingUser.confirmPassword" type="password" class="form-control" id="userReset.confirmPassword" required>
            </div>

            <div class="form-group">
                <a class="btn btn-primary btn-success"  v-on:click.prevent="resetAdminPassword">Reset</a>
                <a class="btn btn-primary btn-danger"  v-on:click.prevent="cancelReset">Cancel</a>
            </div>
        </div>

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
                        name="name" id="inputEmail"
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
                <a class="btn btn-primary btn-success" v-on:click.prevent="saveUser">Register</a>
                <a class="btn btn-primary btn-danger" v-on:click.prevent="cancelRegister">Cancel</a>
            </div>
        </div>

        <div v-if="statistics">
            <h2>Statistics</h2>
            <p>Total Single Player Games: {{this.allStats['singlePlayer']}}</p>
            <p>Total Multi Player Games: {{this.allStats['multiplayer']}}</p>
            <p>Total Played Games: {{this.allStats['totalPlayed']}}</p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nickname</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in this.allStats['winner']"  :key="user.id">
                        <td>{{ user }}</td>

                    </tr>
                    </tbody>
                </table>
        </div>

        <div v-if="myStats">
            <h2>My statistics</h2>
            <p>Total Single Player Games: {{this.myStats['singlePlayer']}}</p>
            <p>Total Multi Player Games: {{this.myStats['multiplayer']}}</p>
            <p>Total Played Games: {{this.myStats['totalPlayed']}}</p>
            <p>Total Wins: {{this.myStats['totalWin']}}</p>
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
                <div class="col-xs-2">
                    <select v-model="selectedGameType">
                        <option v-for="(type, key) in gameType" :key="key" selected="type">{{type}}</option>
                    </select>
                </div>
                <div class="col-xs-2">
                    <div>
                        Max Players
                        <select v-model="selectedNumPlayers">
                            <option v-for="(max, key) in maxPlayers" :key="key" selected="max">{{max}}</option>
                        </select>
                        Bots Difficulty
                        <select v-model="selectedBotType">
                            <option v-for="(bot, key) in botModes" :key="key" selected="bot">{{bot}}</option>
                        </select>
                    </div>
                </div>

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
                allStats:[],
                statistics: false,
                botModes : [0, 1, 2, 3, 4],
                selectedBotType : 0,
                bots : [],
                myStats:false,
                userStats:[],
                showUserProfile:false,
                authUser:{name:'',username:'',email: '', password: '' },
                editUser:{name:'',username:'',email: '', password: '' },
                editingUser : false,
                isAdmin:false,
                resetingUser:{currentPassword:'',newPassword:'',confirmPassword:'',email:''}
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
                    let names = ['Bot.dawey', 'Bot.almeida', 'Bot.DOOM'];
                    for(let i = 0; i < this.selectedNumPlayers -1; i++){
                        this.bots.push({botType: this.selectedBotType, name: names[i]});
                    }

                    //Pedir ao Node para criar o Jogo
                    console.log('Asking Node to ask Laravel if everything is OK');

                    this.$socket.emit('create_game', {
                                                        userID : this.userID,
                                                        gameName: this.gameName,
                                                        gameType: this.selectedGameType,
                                                        gameMaxPlayers: this.selectedNumPlayers,
                                                        rows : this.rows,
                                                        cols : this.cols,
                                                        bots : this.bots,
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
            },
            admin(){
                router.push({ name: 'administraton'});
            },
            saveUser(){
                axios.post('api/user',
                    {"name" : this.newUser.name,
                    "username" : this.newUser.username,
                    "email" : this.newUser.email ,
                    "password" : this.newUser.password })
                    .then(response=>{
                        console.log(response);
                        console.log("New User created");
                        console.log('fake i guess   by: cloro');

                        this.showRegisterDiv = false;
                        this.newUser.name = '';
                        this.newUser.username = '';
                        this.newUser.email = '';
                        this.newUser.password = '';
                    })
                    .catch(error=>{
                        console.log(error);
                    });
            },
            cancelRegister(){
                this.showRegisterDiv = false;
                this.newUser.name = '';
                this.newUser.username = '';
                this.newUser.email = '';
                this.newUser.password = '';

            },
            getOfflineStats(){
                axios.get('api/allStats')
                    .then(response=>{
                        console.log(response.data);
                        Object.assign(this.allStats,response.data);
                        this.statistics = !this.statistics;
                    });
            },getMyStats(){
                console.log(this.currentUser.email);
                axios.get('api/myStats/'+this.currentUser.email)
                    .then(response=>{
                        Object.assign(this.userStats,response.data);
                        this.myStats = !this.myStats;
                    }).catch(error=>{
                    console.log(error);

                });
            },
            showProfile(){
                axios.get('api/user/'+this.currentUser.email)
                    .then(response=>{
                        console.log(response.data);
                        this.authUser.name = response.data.name;
                        this.authUser.username = response.data.nickname;
                        this.authUser.email = response.data.email;
                        //this.authUser.password = response.data.password;
                        this.showUserProfile = !this.showUserProfile;
                    }).catch(error=>{
                    console.log(error);

                });
            },editUserProfile(){
                this.editingUser= !this.editingUser;

            },removeUser(){
                this.clickLogout();
                axios.delete('api/removeAccount/'+this.currentUser.email)
                    .then(response => {
                        console.log(response);
                        console.log('User remove sucessufully');
                        this.showUserProfile=!this.showUserProfile;
                    });

            },desactive(){
                axios.put('api/disable/'+this.currentUser.email
                )
                    .then(response => {
                        console.log(response);
                        console.log('User remove sucessufully');
                        this.showUserProfile=!this.showUserProfile;
                        this.clickLogout();

                    }).catch(function (error){
                    console.log(error);
                });
            },cancelEdit(){
                this.editingUser = false;
            },backEdit(){
                this.showUserProfile = false;
                this.editingUser = false;
            },saveChanges(){

                axios.put('api/user/'+this.authUser.email,
                    {"name" : this.editUser.name,
                    "username" : this.editUser.username,
                    "email" : this.editUser.email ,
                    "password" : this.editUser.password }
                )
                    .then(response => {
                        console.log(response);
                        console.log('User edited sucessufully');
                        this.editingUser = false;

                        this.authUser.name = this.editUser.name;
                        this.authUser.username = this.editUser.username;
                        this.authUser.email = this.editUser.email;
                        this.authUser.password = this.editUser.password;
                    }).catch(function (error){
                    console.log(error);
                });
            },resetPassword(){
                axios.get('api/authUser/'+this.currentUser.email)
                    .then(response=>{
                        console.log(response);
                            let admin = response.data;

                            if(admin == 1){
                                this.isAdmin = !this.isAdmin;
                            }

                    });
            },resetAdminPassword(){
                if(this.resetingUser.newPassword !== this.resetingUser.confirmPassword){
                    //pop up
                }else{
                    this.resetingUser.email = this.currentUser.email;
                    axios.put('api/reset/'+this.resetingUser.email,
                        {"currentPassword":this.resetingUser.currentPassword,
                            "newPassword":this.resetingUser.newPassword
                    })
                        .then(response => {
                            console.log(response);
                        }).catch(function (error){
                        console.log(error);
                    });
                }
            },cancelReset(){
                this.isAdmin = false;
            },clickReset(){
                axios.put('api/resetPassword/' + this.currentUser.email )
                    .then(response => {
                        conolse.log("email send sucessefuly") ;
                    }).catch(function (error){
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
