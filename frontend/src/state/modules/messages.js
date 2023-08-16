import axios from "axios";
export const state = {
    messages: [],
    status: false,
    sms_packages: [],
    purchase_id: '',
    confirm_status: '',
    counter: {
        min: 1,
        sec: 60,
        tryagin: false,
        track: 120,
        sent: false
    },
    products:[]
};
export const getters = {
    Messages: state => state.messages,
    SendStatus: state => state.status,
    SmsPackages: state => state.sms_packages.data,
    purchase_id: state => state.purchase_id,
    ConfirmStatus: state => state.confirm_status,
    ConfirmCountDown: state => state.counter,
    ProductData: state => state.products

};

export const mutations = {
    SET_MESSAGES(state, newValue) {
        state.messages = newValue
    },
    SET_SEND_STATUS(state, newValue) {
        state.status = newValue
    },
    SET_SMS_PACKAGES(state, newValue) {
        state.sms_packages = newValue
    },
    SET_PURCHASE_ID(state, newValue) {
        state.purchase_id = newValue.id
    },
    SET_CONFIRM(state, newValue) {
        state.confirm_status = newValue
    },
    SET_SENT_STATUS(state, newValue) {
        state.counter.sent = newValue
    },
    RESET_SMS_STATE(state) {
        state.counter = {
            min: 1,
            sec: 60,
            tryagin: false,
            track: 120,
            sent: true,
        }
    },
    SET_CONFIRM_STATUS(state, newValue) {
        state.counter.min = newValue.min
        state.counter.sec = newValue.sec
        state.counter.tryagin = newValue.tryagin
        state.counter.track = newValue.track
    },
    SET_PRODUCTS(state, newValue) {
        state.products = newValue
    },
};

export const actions = {
    sendMessage({ commit, dispatch }, payload) {
        commit('SET_SEND_STATUS', true);
        axios.post('messageCenter', payload).then((response) => {
            dispatch('fetchMessageSummary');
            // DISPATCH MESSAGE MODULE
            if (response.status == 200) {
                let message = { 'msg': "Message Sent successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 500) {
                let message = { 'msg': response.msg }
                dispatch('notification/error', message, { root: true })
            }
            commit('SET_SEND_STATUS', false);
        }).catch(() => {
            commit('SET_SEND_STATUS', false)
            let message = { 'msg': "Error Sending Message" }
            dispatch('notification/error', message, { root: true })
        })
    },

    sendMessageGroup({ commit, dispatch }, payload) {
        commit('SET_SEND_STATUS', true);
        axios.post('sendMessageGroup', payload).then((response) => {
            dispatch('fetchMessageSummary');
            // DISPATCH MESSAGE MODULE
            if (response.status == 200) {
                let message = { 'msg': "Group Message Sent successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 500) {
                let message = { 'msg': response.msg }
                dispatch('notification/error', message, { root: true })
            }
            commit('SET_SEND_STATUS', false);
        }).catch(() => {
            commit('SET_SEND_STATUS', false)
            let message = { 'msg': "Error Sending Message to the selected group" }
            dispatch('notification/error', message, { root: true })
        })
    },

    sendMessageProduct({ commit, dispatch }, payload) {

        commit('SET_SEND_STATUS', true);
        axios.post('sendMessageProduct', payload).then((response) => {
            dispatch('fetchMessageSummary');
            // DISPATCH MESSAGE MODULE
            if (response.status == 200) {
                let message = { 'msg': "Product Message Sent successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 500) {
                let message = { 'msg': response.msg }
                dispatch('notification/error', message, { root: true })
            }
            commit('SET_SEND_STATUS', false);
        }).catch(() => {
            commit('SET_SEND_STATUS', false)
            let message = { 'msg': "Error Sending Message to the selected Product Clients" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchMessageSummary({ commit }) {
        axios.get('messageCenter').then(e_res => {
            commit('SET_MESSAGES', e_res.data)
        })
    },

    // SMS MANAGEMENT
    fetchsmsPackages({ commit }) {
        axios.get('fetch_packages').then(e_res => {
            commit('SET_SMS_PACKAGES', e_res.data)
        })
    },

    buysmsPackages({ commit, dispatch, state }, payload) {
        if (state.counter.sent) {
            return
        }
        commit('SET_SENT_STATUS', true);
        commit('SET_SEND_STATUS', true);
        axios.post('buysms', payload).then((response) => {
            commit('SET_SEND_STATUS', false);
            commit('RESET_SMS_STATE');
            commit('SET_PURCHASE_ID', response.data);
            dispatch('checkSmsBuyStatus')
            dispatch('ConfrimCoutDown')
        }).catch((eror) => {
            commit('SET_SEND_STATUS', false)
            commit('SET_SENT_STATUS', false);
            let message = { 'msg': "Error initializing payment" }
            dispatch('notification/error', message, { root: true })
            if (eror.response.status == 401) {
                dispatch('auth/logout', null, { root: true })
            }
        })
    },
    checkSmsBuyStatus({ commit, state }) {
        var refreshIntervalId = setInterval(() => {
            axios.get(`fetchsmsstatus/${state.purchase_id}`).then(e_res => {
                commit('SET_CONFIRM', e_res.data)
                commit('SET_SENT_STATUS', true);
            })
        }, 5000);

        setTimeout(() => {
            clearInterval(refreshIntervalId);
            commit('SET_SENT_STATUS', false);
        }, 120000);
        setTimeout(() => {
            commit('SET_SENT_STATUS', false);
        }, 120000);

    },

    ConfrimCoutDown({ commit, state }) {
        var intervalID = setInterval(() => {
            if (state.counter.track == 60) {
                commit('SET_CONFIRM_STATUS', {
                    min: 0,
                    sec: 59,
                    tryagin: false,
                    track: 59,
                    sent: true

                })
            } else if (state.counter.track == 0 && state.counter.sec == 0) {
                commit('SET_CONFIRM_STATUS', {
                    min: 0,
                    sec: 0,
                    tryagin: true,
                    track: 0,
                    sent: false
                })

            } else if (state.counter.track > 60) {
                commit('SET_CONFIRM_STATUS', {
                    min: 1,
                    sec: state.counter.sec - 1,
                    tryagin: false,
                    track: state.counter.track - 1,
                    sent: true
                })
            } else {
                if (state.counter.track == 0 && state.counter.sec == 0) {
                    commit('SET_CONFIRM_STATUS', {
                        min: 0,
                        sec: 0,
                        tryagin: true,
                        track: 0,
                        sent: false
                    })
                } else {
                    commit('SET_CONFIRM_STATUS', {
                        min: 0,
                        sec: state.counter.sec - 1,
                        tryagin: false,
                        track: state.counter.track - 1,
                        sent: true
                    })
                }

            }

        }, 1000);

        setTimeout(() => {
            clearInterval(intervalID);
            commit('SET_SENT_STATUS', false);
        }, 120000);

    },

    fetchProductsData({ commit }, payload) {
        axios.post('fetch_products', payload).then(e_res => {
            commit('SET_PRODUCTS', e_res.data)
        })
    },


};