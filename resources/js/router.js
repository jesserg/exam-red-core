import {createWebHistory, createRouter} from "vue-router";

import Home from './pages/Home.vue';
import Register from './pages/Register.vue';
import Login from './pages/Login.vue';
import Dashboard from './pages/Dashboard.vue';

import Roles from './components/Roles';
import EditRole from './components/EditRole';
import AddRole from './components/AddRole';

import Users from './components/Users';
import AddUser from './components/AddUser';
import EditUser from './components/EditUser';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'roles',
        path: '/roles',
        component: Roles
    },
    {
        name: 'editrole',
        path: '/roles/edit',
        component: EditRole
    },
    {
        name: 'addrole',
        path: '/roles/add',
        component: AddRole
    },
    {
        name: 'users',
        path: '/users',
        component: Users
    },
    {
        name: 'adduser',
        path: '/users/add',
        component: AddUser
    },
    {
        name: 'edituser',
        path: '/users/edit',
        component: EditUser
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;