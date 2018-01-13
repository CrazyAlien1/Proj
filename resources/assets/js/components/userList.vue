<template>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.nickname }}</td>
                <td>
                    <a class="btn btn-xs btn-primary" v-if="!user.blocked" v-on:click.prevent="blockUser(user)">Block</a>
                    <a class="btn btn-xs btn-success" v-if="user.blocked" v-on:click.prevent="reativeUser(user)">Reactive</a>
                    <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- blocking user -->
        <div v-if="blockingUser">
            <div class="jumbotron" >
                <h2>Blocked User {{this.currentUser.name}} </h2>
                <div class="form-group">
                    <label for="inputReasonBlocked">Reason to block user</label>
                    <input
                            type="text" class="form-control" v-model="currentUser.reason_blocked"
                            name="name" id="inputReasonBlocked"
                            placeholder="Why blocked that user" value="" />
                </div>
                <div class="form-group">
                    <a class="btn btn-danger" v-on:click.prevent="confirmBlock">Block</a>
                    <a class="btn btn-primary" v-on:click.prevent="blockingUser = false">Cancel</a>
                </div>
            </div>
        </div>

        <!-- reactiving user -->
        <div v-if="reactivingUser">
            <div class="jumbotron" >
                <h2>Reactive User {{this.currentUser.name}}</h2>
                <div class="form-group">
                    <label for="inputReasonReactived">Reason to reactive user</label>
                    <input
                            type="text" class="form-control" v-model="currentUser.reason_reactivated"
                            name="name" id="inputReasonReactived"
                            placeholder="Why reactive that user" value="" />
                </div>
                <div class="form-group">
                    <a class="btn btn-success" v-on:click.prevent="confirmReactive">Reative</a>
                    <a class="btn btn-primary" v-on:click.prevent="reactivingUser = false">Cancel</a>
                </div>
            </div>
        </div>

        <!-- deleting user -->
        <div v-if="deletingUser">
            <div class="jumbotron" >
                <h2>Delete User {{this.currentUser.name}}</h2>
                <div class="form-group">
                    <label for="inputDeleteReactived">Reason to delete user</label>
                    <input
                            type="text" class="form-control" v-model="ressonRemoved"
                            name="name" id="inputDeleteReactived"
                            placeholder="Why remove that user" value="" />
                </div>
                <div class="form-group">
                    <a class="btn btn-danger" v-on:click.prevent="confirmRemove">Remove</a>
                    <a class="btn btn-primary" v-on:click.prevent="deletingUser = false">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code (not registered)
    module.exports={
        props:["users"],
        data: function () {
            return {
                blockingUser : false,
                currentUser: null,
                reactivingUser: false,
                deletingUser:false,
                ressonRemoved: '',
                message: '',
            }
        },
        methods:{
            blockUser(user){
                this.currentUser = user;
                this.blockingUser=true;
            },
            deleteUser(user){
                this.currentUser=user;
                this.deletingUser = true;

            },
            reativeUser(user){
                this.reactivingUser = true;
                this.currentUser = user;
            },
            confirmBlock(){
                this.blockingUser = false;
                axios.put('api/user/block/'+this.currentUser.id,
                    {"reason_blocked" : this.currentUser.reason_blocked }
                )
                    .then(response => {
                        let index = this.users.findIndex(response.data.id);

                        if(index>-1){
                            this.users[index]=response.data;
                            console.log('User blocked successfully');
                            this.message = 'User blocked successfully!';
                        }

                    }).catch(function (error){
                        console.log(error);
                });
            },
            confirmReactive(){
                this.reactivingUser = false;

                axios.put('api/user/unblock/'+this.currentUser.id,
                    {"reason_reative" : this.currentUser.reason_reactivated }
                )
                    .then(response => {
                        let index = this.users.findIndex(response.id);

                        if(index>-1){
                            this.users[index]=response;
                            console.log('User reativated successfully');
                            this.message = 'User reactivated successfully!';
                        }
                    });
            },
            confirmRemove(){
                this.deletingUser = false;
                axios.delete('api/user/'+this.currentUser.id,{"reason_remove" : this.ressonRemoved })
                    .then(response => {
                        //console.log(response);
                        console.log('User removed sucessufully');
                        this.message = 'User removed successfully!';

                    });
            }
        }
    }
</script>

<style scoped>
    /*	  Specific style applied only on the component*/
</style>
