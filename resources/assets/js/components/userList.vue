<template>
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
        <tr v-for="user in users"  :key="user.id">
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

    <div v-if="blockingUser">
        <div class="jumbotron" >
            <h2>Blocked User</h2>
            <div class="form-group">
                <label for="inputReasonBlocked">Reason to block user</label>
                <input
                        type="text" class="form-control" v-model="user.reason_blocked"
                        name="name" id="inputReasonBlocked"
                        placeholder="Why blocked that user" value="" />
            </div>
            <div class="form-group">
                <a class="btn btn-default" v-on:click.prevent="confirmBlock()">Block</a>
                <a class="btn btn-default" v-on:click.prevent="cancelBlock()">Cancel</a>
            </div>
        </div>
    </div>

    <div v-if="reactivingUser">
        <div class="jumbotron" >
            <h2>Reactive User</h2>
            <div class="form-group">
                <label for="inputReasonReactived">Reason to reactive user</label>
                <input
                        type="text" class="form-control" v-model="user.reason_reactivated"
                        name="name" id="inputReasonReactived"
                        placeholder="Why blocked that user" value="" />
            </div>
            <div class="form-group">
                <a class="btn btn-default" v-on:click.prevent="confirmReactive()">Reactive</a>
                <a class="btn btn-default" v-on:click.prevent="cancelReactive()">Cancel</a>
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
            }
        },
        methods:{
            blockUser(user){
                this.blockingUser=true;
                this.currentUser=user;
            },
            deleteUser(user){
                axios.delete('api/user/'+user.id)
                    .then(response => {
                        console.log('User successfully deleted');
                    });
            },
            reativeUser(user){
                user.blocked=0;
            },
            confirmBlock(){
                this.currentUser.blocked=1;
                this.blockingUser=false;
                //enviar email :)
            },
            cancelBlock(){
                this.blockingUser=false;
            },
            confirmReactive(){
                this.currentUser.blocked=0;
                this.reactivingUser=false;
                //enviar email :)
            },
            cancelReactive(){
                this.reactivingUser=false;
            }
        }
    }
</script>

<style scoped>
    /*	  Specific style applied only on the component*/
</style>