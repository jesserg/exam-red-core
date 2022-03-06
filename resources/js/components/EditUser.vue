<template>
    <div>
        <h4 class="text-center">Edit User</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="editUser">
                    <div class="alert alert-success" role="alert" v-if="success">
                        User successfully updated. <router-link to="/users" >View list</router-link>
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="error">
                        {{ error }}
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Input name" required v-model="user.name">
                    </div>
                    <div class="form-group mt-2">
                        <label>Email address</label>
                        <input type="email" class="form-control" placeholder="Input email address" required v-model="user.email">
                    </div>
                     <div class="form-group mt-2">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Input password" v-model="user.password">
                    </div>
                     <div class="form-group mt-2">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm password" v-model="user.confirm_password">
                    </div>
                    <div class="form-group mt-2">
                        <label>Role</label>
                        <select class="form-control" required v-model="user.role">
                            <option value="" selected>Select role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
                        </select>
                    </div>
                    <router-link to="/users" class="btn btn-secondary text-light mt-2">Cancel</router-link>
                    <button type="submit" class="btn btn-primary text-light mt-2" style="margin-left:6px;">Update User</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            roles: {},
            user: {},
            error: null,
            success: false,
        }
    },
    created() {
        this.getUser();
        this.getRoles();
        
    },
    methods: {
        editUser() {
            let dis = this;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.put(`/api/users/${this.$route.params.id}`, dis.user)
                    .then(response => {
                        if(response.data.status == 'success'){
                            dis.success = true;
                            dis.error = null;
                            //empty
                            dis.user.name = null;
                            dis.user.email = null;
                            dis.user.password = null;
                            dis.user.confirm_password = null;
                            dis.user.role = null;
                        }else{
                            dis.error =  response.data.message;
                        }
                    })
                    .catch(function (error) {
                        dis.error = 'Something went wrong. Please try again later.';
                        console.log(error);
                    });
            })
        },
        getRoles(){
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
        getUser(){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/users/${this.$route.params.id}`)
                .then(response => {
                    this.user = response.data;
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