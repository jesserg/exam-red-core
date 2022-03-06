<template>
    <div>
        <h4 class="text-center">Add Role</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addRole">
                    <div class="alert alert-success" role="alert" v-if="success">
                        Role successfully added. <router-link to="/roles" >View list</router-link>
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="error">
                        {{ error }}
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Input name" required v-model="role.name">
                    </div>
                    <div class="form-group mt-2">
                        <label>Description</label>
                        <input type="text" class="form-control" placeholder="Input description" required v-model="role.description">
                    </div>
                    <button type="submit" class="btn btn-primary text-light mt-2">Add Role</button>
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
    methods: {
        addRole(event) {
            let dis = this;
            let dis_event = event;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/roles', dis.role)
                    .then(response => {
                        //this.$router.push({name: 'roles'})
                        console.log(response.data);
                        if(response.data.status == 'success'){
                            dis.success = true;
                            dis.error = null;
                            //empty
                            dis.role.name = null;
                            dis.role.description = null;
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