import axios from "axios";
import router from '../../router/index';
export const state = {
    payment_options: [],
    money_transfers: [],
    general_settings: [],
    receipt_settings:[],
    save_status: false,
    statement: [],
    filter_status:false,
    message:''
};
export const getters = {
    PaymentOptions: state => state.payment_options.data,
    MoneyTransfers: state => state.money_transfers.data,
    GeneralSettings: state => state.general_settings,
    ReceiptSettings: state => state.receipt_settings,
    SaveStatus: state => state.save_status,
    Statement: state => state.statement,
    filterStatus: state => state.filter_status,
    Message: state => state.message,

 
};

export const mutations = {
    SET_PAYMENT_OPTIONS(state, newValue) {
        state.payment_options = newValue
    },
    SET_MONEY_TRANSFERS(state, newValue) {
        state.money_transfers = newValue
    },
    SET_GENERAL_SETTINGS(state, newValue) {
        state.general_settings = newValue
    },
    SET_RECEIPT_SETTINGS(state, newValue) {
        state.receipt_settings = newValue
    },

    SET_STATEMENT(state, newValue) {
       state.statement = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_FILTER_STATUS(state, newValue) {
        state.filter_status = newValue
    },
    SET_MESSAGE(state, newValue) {
        state.message = newValue
    },
   
};

export const actions = {
    addOption({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('payment_options',payload).then(() => {
            dispatch('fetchPaymentOptions');
            let message = { 'msg': `Payment Option ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Payment Option "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Payment Option
    editOption({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`payment_options/${payload.id}`, payload).then(() => {
            dispatch('fetchPaymentOptions');
            let message = { 'msg': `Payment Option ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Payment Option" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Payment Option
    deleteOption({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`payment_options/${payload.id}`).then(() => {
            dispatch('fetchPaymentOptions');
            let message = { 'msg': "Payment Option deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error "+error }
            dispatch('notification/error', message, { root: true })
        })
    },

    changeDefaultOption({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('change_default_option',payload).then(() => {
            dispatch('fetchPaymentOptions');
            let message = { 'msg': `Payment Option ${payload.name} set as default` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error changing default Payment Option "+error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Add Customer Appreciation Message
    SaveMessage({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('general_settings',payload).then(() => {
            dispatch('FetchMessage');
            let message = { 'msg': `Message has been saved successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding message "+error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Add Customer Appreciation Message    

    FetchMessage({ commit }) {
        axios.get('fetch_message').then(ec_res => {
            commit('SET_MESSAGE', ec_res.data)
        })
    },

    // Edit General settings
    ChangeGeneralSettings({commit},payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`general_settings/${payload.id}`, payload).then(() => {
            // dispatch('fetchGeneralSettings');
            // commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            // commit('SET_SAVE_STATUS', false)
            // let message = { 'msg': "Error updating Payment Option" }
            // dispatch('notification/error', message, { root: true })
        })
    },
    // Edit General settings
    ChangeReceiptSettings({commit},payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`receipt_settings/${payload.id}`, payload).then(() => {
            // dispatch('fetchGeneralSettings');
            // commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            // commit('SET_SAVE_STATUS', false)
            // let message = { 'msg': "Error updating Payment Option" }
            // dispatch('notification/error', message, { root: true })
        })
    },
    //add money transfer
    addMoneyTransfer({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('money_transfers',payload).then(() => {
            dispatch('fetchMoneyTransfers');
            let message = { 'msg': `Money Transfer recorded successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Recording Money Transfer "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete money transfer Option
    deleteMoneyTransfer({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`money_transfers/${payload.id}`).then(() => {
            dispatch('fetchMoneyTransfers');
            let message = { 'msg': "Money Transfer  deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    fetchPaymentOptions({ commit }) {
        axios.get('payment_options').then(ec_res => {
            commit('SET_PAYMENT_OPTIONS', ec_res.data)
        })
    },
    fetchMoneyTransfers({ commit }) {
        axios.get('money_transfers').then(ec_res => {
            commit('SET_MONEY_TRANSFERS', ec_res.data)
        })
    },
    fetchStatement({ commit }, payload) {        
        commit('SET_FILTER_STATUS', true)
        axios.post('statement', payload).then(ec_res => {
            commit('SET_STATEMENT', ec_res.data)
            commit('SET_FILTER_STATUS', false)
        })
    },
    fetchGeneralSettings({ commit, dispatch }) {
        axios.get('general_settings').then(ec_res => {
            commit('SET_GENERAL_SETTINGS', ec_res.data)
        }).catch(() => {
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            dispatch('auth/authenticated', null, { root: true });
            router.push('/login').catch(() => {});
        })
    },
    fetchReceiptSettings({ commit }) {
        axios.get('receipt_settings').then(ec_res => {
            commit('SET_RECEIPT_SETTINGS', ec_res.data)
        })
    },
  
};