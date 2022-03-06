<template>
    <div>
        <h4 class="text-center">Edit Role</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="updateRole">
                     <div class="alert alert-success" role="alert" v-if="success">
                        Role successfully updated <router-link to="/roles" >View list</router-link>
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="error">
                        {{ error }}
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="role.role_name">
                    </div>
                    <div class="form-group mt-2">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="role.description">
                    </div>
                    <button type="submit" class="btn btn-primary text-light mt-2">Update Role</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            role: {},
            error: null,
            success: false,
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/roles/${this.$route.params.id}`)
                .then(response => {
                    this.role = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        updateRole() {
            let dis = this;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.put(`/api/roles/${this.$route.params.id}`, this.role)
                    .then(response => {
                        console.log(response.data);
                        if(response.data.status == 'success'){
                            dis.success = true;
                            dis.error = null;
                        }else{
                            dis.error =  response.message;
                        }
                    })
                    .catch(function (error) {
                        dis.error = 'Something went wrong. Please try again later.';
                        console.log(error);
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