<template>
    <div class="row">
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
                statistichs : false,
                stats:[],
                show : false,
                usersData:[],
                file : undefined,
                showUploadInput:false,
                authUser:undefined,
            }
        },

        methods: {
            getUsers(){
                axios.get('api/users')
                    .then(response=>{this.users = response.data.data;});
            },
            getGames(){
                axios.get('api/games')
                    .then(response=>{this.games = response.data.data;});
            },showStatistic(){
                this.getStatistiscs();

                if(this.statistichs == true){
                    this.statistichs = false;
                }else {
                    this.statistichs = true;
                }
                this.show = false;
            },getStatistiscs(){
                axios.get('api/statistics')
                    .then(response=>{Object.assign(this.stats, response.data);});
            },showList(){
                axios.get('/api/users/order')
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
