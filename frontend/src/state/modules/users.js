import axios from "axios";
export const state = {
    roles: [],
    users: [],
    save_status: false,
    permissions: [],
    myperm: '',
    access_time:[]
};
export const getters = {
    Roles: state => state.roles.data,
    Users: state => state.users.data,
    SaveStatus: state => state.save_status,
    Permissions: state => state.permissions,
    MyUserPerm: state => state.myperm,
    UserAccesstime: state => state.access_time.data,

};

export const mutations = {
    SET_ROLES(state, newValue) {
        state.roles = newValue
    },
    SET_USERS(state, newValue) {
        state.users = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_PERMISSIONS(state, newValue) {
        state.permissions = newValue
    },
    SET_MY_PERM(state, newValue) {
        state.myperm = newValue
    },
    SET_ACCESSTIME(state, newValue) {
        state.access_time = newValue
    },

};

export const actions = {
    addRole({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('roles', payload).then((resp) => {
            dispatch('fetchRoles');
            if (resp.status == 201) {
                let message = { 'msg': resp.message }
                dispatch('notification/error', message, { root: true })
            } else {
                let message = { 'msg': `User Group ${payload.name} added successfully` }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding User Group" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Edit Role
    editRole({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`roles/${payload.id}`, payload).then(() => {
            dispatch('fetchRoles');
            let message = { 'msg': "Cash in record updated successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Cash in record" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Delete Role 
    deleteRole({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`roles/${payload.id}`).then(() => {
            dispatch('fetchRoles');
            let message = { 'msg': `User Group ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Deleting User Group" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add User
    addUser({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('users', payload).then((resp) => {
            if (resp.status == 201) {

                let message = { 'msg': resp.data.message }
                dispatch('notification/error', message, { root: true })
            } else {
                dispatch('fetchUsers');
                let message = { 'msg': `User ${payload.name} added successfully` }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding User" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Edit User
    editUser({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`users/${payload.id}`, payload).then((resp) => {
            if (resp.status == 201) {

                let message = { 'msg': resp.data.message }
                dispatch('notification/error', message, { root: true })
            } else {
                dispatch('fetchUsers');
                let message = { 'msg': `User ${payload.name} details updated successfully` }
                dispatch('notification/success', message, { root: true })
                commit('SET_SAVE_STATUS', false);
            }

        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating User" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // change Password
    changePassword({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('change_password', payload).then(() => {
            let message = { 'msg': "Password Changed successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Changing Password" }
            dispatch('notification/error', message, { root: true })
        })
    },


    // Delete User 
    deleteUser({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`users/${payload.id}`).then(() => {
            dispatch('fetchUsers');
            let message = { 'msg': "User status changed successfully" };
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Changing User Status" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchRoles({ commit }) {
        axios.get('roles').then(res => {
            commit('SET_ROLES', res.data)
        })
    },
    fetchUsers({ commit }) {
        axios.get('users').then(res => {
            commit('SET_USERS', res.data)
        })
    },

    fetchPermissions({ commit }) {
        axios.get('permissions').then(res => {
            commit('SET_PERMISSIONS', res.data)
        })
    },

    // change permissions
    changePermissions({ dispatch }, payload) {
        // commit('SET_SAVE_STATUS', true);
        axios.put(`permissions/${payload.id}`, payload).then(() => {
            dispatch('fetchMyPerm', payload.id);
            dispatch('auth/authenticated');
        }).catch(() => {

        })
    },

    fetchMyPerm({ commit }) {
        axios.get(`permissions/${0}`).then(ec_res => {
            commit('SET_MY_PERM', ec_res.data)
        })
    },

    // Access time
    fetchAccesstime({ commit }) {
        axios.get(`fetch_accesstime`).then(ec_res => {
            commit('SET_ACCESSTIME', ec_res.data)
        })
    },

    ChangeAccesstime({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('change_accesstime', payload).then(() => {
            let message = { 'msg': "Access time successfully" }
            dispatch('fetchAccesstime');
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error in changing access time" }
            dispatch('notification/error', message, { root: true })
        })
    },

    UpdateDays({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('change_accessdays', payload).then(() => {
            let message = { 'msg': "Access days successfully" }
            dispatch('fetchAccesstime');
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error in changing access days" }
            dispatch('notification/error', message, { root: true })
        })
    },


};