<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Department</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in users"  :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.age }}</td>
            <td>{{ user.department }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script type="text/javascript">
    // Component code (not registered)
    module.exports={
        props:["users"],
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