<template>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Nick Name</th>
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
                    <a class="btn btn-xs btn-primary" v-if="user.blocked" v-on:click.prevent="reativeUser(user)">Reactive</a>
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
                    <a class="btn btn-default" v-on:click.prevent="confirmBlock">Block</a>
                    <a class="btn btn-default" v-on:click.prevent="cancelBlock">Cancel</a>
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
                    <a class="btn btn-default" v-on:click.prevent="confirmReactive">Reative</a>
                    <a class="btn btn-default" v-on:click.prevent="cancelReactive">Cancel</a>
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
                    <a class="btn btn-default" v-on:click.prevent="confirmRemove">Remove</a>
                    <a class="btn btn-default" v-on:click.prevent="cancelRemove">Cancel</a>
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
                            console.log('User blocked sucessufully');
                        }

                    }).catch(function (error){
                        console.log(error);
                });
            },
            cancelBlock(){
                this.blockingUser = false;
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
                            console.log('User reative sucessufully');
                        }
                    });
            },
            cancelReactive(){
                this.reactivingUser = false;
            },confirmRemove(){
                this.deletingUser = false;
                axios.delete('api/user/'+this.currentUser.id,{"reason_remove" : this.ressonRemoved })
                    .then(response => {
                        console.log(response);
                        console.log('User remove sucessufully');

                    });
            },cancelRemove(){
                this.deletingUser = false;
            }
        }
    }
</script>

<style scoped>
    /*	  Specific style applied only on the component*/
</style>