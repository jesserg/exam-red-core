<template>
    <div>
        <h4 class="text-center">User Lists</h4><br/>
        <div class="alert alert-success" role="alert" v-if="success">
            User successfully deleted
        </div>
        <div class="alert alert-danger" role="alert" v-if="error">
            {{ error }}
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.role.role_name }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edituser', params: { id: user.id }}" class="btn btn-primary text-light">Edit
                        </router-link>
                        <button class="btn btn-danger text-light" @click="deleteUser(user.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-info text-light" @click="this.$router.push('/users/add')">Add User</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            error: null,
            success: false,
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/users')
                .then(response => {
                    this.users = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        deleteUser(id) {
            let dis = this;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/users/${id}`)
                    .then(response => {
                        if(response.data.status == 'success'){
                            dis.success = true;
                            let i = dis.users.map(item => item.id).indexOf(id); // find index of your object
                            dis.users.splice(i, 1)
                        }else{
                            dis.error =  response.data.message;
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>