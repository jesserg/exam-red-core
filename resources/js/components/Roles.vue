<template>
    <div>
        <h4 class="text-center">Role Lists</h4><br/>
        <div class="alert alert-success" role="alert" v-if="success">
            Role successfully deleted
        </div>
        <div class="alert alert-danger alert-dismissible" role="alert" v-if="error">
            {{ error }}
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="role in roles" :key="role.id">
                <td>{{ role.id }}</td>
                <td>{{ role.role_name }}</td>
                <td>{{ role.description }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'editrole', params: { id: role.id }}" class="btn btn-primary text-light">Edit
                        </router-link>
                        <button class="btn btn-danger text-light" @click="deleteRole(role.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-info text-light" @click="this.$router.push('/roles/add')">Add Role</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            roles: {},
            error: null,
            success: false,
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/roles')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        deleteRole(id) {
            let dis = this;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/roles/${id}`)
                    .then(response => {
                        if(response.data.status == 'success'){
                            dis.success = true;
                            let i = dis.roles.map(item => item.id).indexOf(id); // find index of your object
                            dis.roles.splice(i, 1)
                        }else{
                            dis.error =  response.data.message;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                        dis.error = 'Something went wrong. Please try again later.';
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