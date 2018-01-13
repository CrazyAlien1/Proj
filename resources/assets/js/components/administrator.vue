<template>
    <div class="row">
<<<<<<< HEAD
        <h2 class="panel-heading">Administration</h2>

        <div class="panel-body">
            <a class="btn btn-primary" href="http://dadproj.dad/">Back</a>

            <button class="btn btn-primary" @click.prevent="showStatistic">Statistics</button>
            <button class="btn btn-default" @click.prevent="showUsers=!showUsers">Users</button>
            <button class="btn btn-primary" @click.prevent="showGames=!showGames">Games</button>

            <!-- Statistics -->
            <div v-if="statistics">
                <h3>Statistics</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Total singleplayer games played:</th>
                        <th>Total multiplayer games played:</th>
                        <th>Total games played:</th>
                        <th>Total wins:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in usersData"  :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.nickname }}</td>
                        <div v-for="userGame in usersGamesPlayed">
                            <td v-if="user.name === userGame.name && userGame.type == 'singleplayer'">{{ userGame.totalGames }}</td>
                            <td v-else>0</td>
                            <td v-if="user.name === userGame.name && userGame.type == 'multiplayer'">{{ userGame.totalGames }}</td>
                            <td v-else>0</td>

                            <!--FALTA AQUI-->
                            <td>totalplay</td>
                            <td>totalwin</td>
                        </div>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="showUsers">
                <h3>Users</h3>
                <user-list :users="users">
                </user-list>
            </div>

            <div v-if="showGames">
                <h3>Games</h3>
                <game-list :games="games">
                </game-list>
            </div>

            <div v-if="!showGames && !showUsers && !statistics">
                <h2 class="text-center">Press the button to start your activity</h2>
=======
        <div class="container">
            <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Administration</div>

                            <div class="panel-body">
                                Hello Administrator
                                <p></p>

                                <a class="btn btn-xs btn-primary" v-on:click.prevent="showUpload">Upload</a>
                                <div v-if="showUploadInput">
                                    <input style="display: inline-block" type="file" @change="processFile($event)">
                                    <a class="btn btn-primary btn-success" v-on:click.prevent="uploadImages">Upload</a>
                                </div>

                                <a class="btn btn-xs btn-primary" v-on:click.prevent="showStatistic">Statistics</a>

                                <!-- Statistics -->
                                <div v-if="statistichs">
                                    <h2>Statistics</h2>
                                    <p>Total Single Player Games: {{this.stats['singlePlayer']}}</p>
                                    <p>Total Multi Player Games: {{this.stats['multiplayer']}}</p>
                                    <p>Total Played Games: {{this.stats['totalPlayed']}}</p>
                                    <a class="btn btn-xs btn-primary" v-on:click.prevent="showList">List of all User</a>
                                </div>

                                <div v-if="show">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Nick Name</th>
                                            <th>Total single Player games played:</th>
                                            <th>Total multilayer Player games played:</th>
                                            <th>Total games played:</th>
                                            <th>Total Wins:</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="user in usersData"  :key="user.id">
                                            <td>{{ user.name }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>{{ user.nickname }}</td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <user-list :users="users">

                                </user-list>

                                <game-list :games="games">

                                </game-list>
                        </div>
                    </div>
                </div>
>>>>>>> ecf094f6fc829d74e1738bd8bcdcc1177dab8841
            </div>
        </div>
    </div>

</template>

<script type="text/javascript">
    import userList from './userList.vue';
    import gameList from './gameList.vue';

    export default {
        data: function(){
            return {
                users: [],
                games: [] ,
                statistics : false,
                stats:[],
                show : false,
                usersData:[],
<<<<<<< HEAD
                usersGamesPlayed:[],
                singleGames: undefined,
                showGames: false,
                showUsers: false,
=======
                file : undefined,
                showUploadInput:false,
                authUser:undefined,
>>>>>>> ecf094f6fc829d74e1738bd8bcdcc1177dab8841
            }
        },

        methods: {
            getUsers(){
                axios.get('api/users')
                    .then(response=>{
                        this.users = response.data.data;
                    })
                    .catch(error=>{
                        console.log(error);
                    });
            },
            getGames(){
                axios.get('api/games')
                    .then(response=>{
                        this.games = response.data.data;
                    })
                    .catch(error=>{
                        console.log(error);
                    });
            },
            showStatistic(){
                this.getStatistics();
                this.statistics = !this.statistics;
            },
            getStatistics(){
                this.showList();
                axios.get('api/statistics')
                    .then(response=>{
                        Object.assign(this.stats, response.data);
                    })
                    .catch(error=>{
                        console.log(error);
                    });
            },
            showList(){
                axios.get('/api/users/order')
<<<<<<< HEAD
                    .then(response=>{
                        Object.assign(this.usersData, response.data);
                        console.log(response);
                        axios.get('api/users/gamesplayed')
                            .then(resp=>{
                                Object.assign(this.usersGamesPlayed, resp.data);
                                console.log(resp);
                            })
                            .catch(errorGames=>{
                                console.log('Second axios!!:: ' + errorGames);
                            })
                    })
                    .catch(error=>{
                        console.log('First axios!!:: ' + error);
=======
                    .then(response=>{Object.assign(this.usersData, response.data);});

                if(this.show == true){
                    this.show = false;
                }else {
                    this.show = true;
                }
            },showUpload(){
                this.showUploadInput = !this.showUploadInput;
            },processFile(event) {
                console.log(event);
                console.log(event.target.files[0]);
                this.file = event.target.files[0]
            },uploadImages(){
                const formData = new FormData();
                formData.append( 'image', this.file );
                axios.post('api/upload',
                    {"image" : formData})
                    .then(response=>{
                        console.log("Uploaded sucessefully");
                    })
                    .catch(error=>{
                        console.log(error);
>>>>>>> ecf094f6fc829d74e1738bd8bcdcc1177dab8841
                    });
            },
        },
        components: {
            'user-list' : userList,
            'game-list': gameList,
        },
        computed: {

        },
        mounted() {
            this.getUsers();
            this.getGames();
        }

    }
</script>

<style>

</style>
