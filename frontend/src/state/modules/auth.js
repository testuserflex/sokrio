import axios from "axios";
import router from '../../router/index';
export const state = {
    user: null,
    loggedIn: false,
    token: null,
    license: false,
    loginstatus: false,
    login_errors: null,
    branches:[]
};
export const getters = {
    name: state => { return state.user ? state.user.names : null },
    token: state => { return state.token },
    username: state => { return state.user ? state.user.uname : null },
    email: state => { return state.user ? state.user.uemail : null },
    business: state => { return state.user ? state.user.business.name : null },
    branch: state => { return state.user.branch ? state.user.branch.name : null },
    branchid: state => { return state.user ? state.user.branch_id : null },
    User: state => { return state.user ? state.user : null },
    MyUserPerm: state => { return state.user ? state.user.role : null },
    loginStatus: state => state.loginstatus,
    login_errors: state => state.login_errors,
    Branches: state => state.branches,

};

export const mutations = {
    setLoggedIn(state, newValue) {
        state.user = newValue.user,
            state.token = newValue.token,
            state.loggedIn = true
    },
    LoggedOut(state) {
        state.user = null,
            state.token = null,
            state.loggedIn = false
    },

    initiateAuth: (state) => {
        let token = localStorage.getItem('token')
        if (token) {
            state.token = token
            state.loggedIn = true
        }

    },
    loginFailure(state, errors) {
        state.login_errors = errors;
    },
    SET_LOGIN_STATUS(state, newValue) {
        state.loginstatus = newValue;
    },
    SET_BRANCHES(state, newValue) {
        state.branches = newValue;
    },

};

export const actions = {
    login({ commit }, payload) {
        commit('SET_LOGIN_STATUS', true)
        axios.post('/login', payload)
            .then((res) => {                
                if (res.status == 200) {
                    commit('SET_LOGIN_STATUS', false)
                    commit('loginFailure', '');
                    if (res.data.user.initial_visit == 0) {
                        localStorage.setItem("username", res.data.user.uname)
                        router.push('/update_password').catch(() => {});
                    } else {
                        let token = res.data.token;
                        commit('setLoggedIn', res.data);
                        localStorage.setItem('token', token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
                        router.push('/');
                    }                    
                }
                if(res.status == 202 || res.status == 401){
                    commit('SET_LOGIN_STATUS', false)
                    commit('loginFailure', res.data.message);
                }
            })
            .catch((error) => {
                commit('SET_LOGIN_STATUS', false)
                commit('loginFailure', error);
            });
    },
    authenticated({ commit, dispatch }) {
        axios.get('/user')
            .then((res) => {
                let token = localStorage.getItem('token')
                let data = {
                    'token': token,
                    'user': res.data.data
                }
                commit('setLoggedIn', data);
            })
            .catch(() => {
                if(localStorage.getItem('token')){
                    dispatch('logout');
                }
                commit('LoggedOut');                
            });
    },
    logout({ commit }) {
        axios.post('/logout').then(() => {
            localStorage.removeItem('token');
            commit('LoggedOut')
            delete axios.defaults.headers.common['Authorization'];
            router.push('/auth/login');
        }).catch(() => {});
    },

    fetchBranches({ commit }) {
        axios.get('fetch_branches').then(ec_res => {
            commit('SET_BRANCHES', ec_res.data)
        })
    },

};