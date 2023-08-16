import axios from "axios";
export const state = {
    cashout: [],
    cashin: [],
    save_status: false,
    transactions:[]
};
export const getters = {
    CashIn: state => state.cashin.data,
    CashOut: state => state.cashout.data,
    SaveStatus: state => state.save_status,
    GetTransactions: state => state.transactions.data,
 
};

export const mutations = {
    SET_CASHIN(state, newValue) {
        state.cashin = newValue
    },
    SET_CASHOUT(state, newValue) {
        state.cashout = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_TRANSACTIONS(state, newValue) {
        state.transactions = newValue
    },
   
};

export const actions = {
     recordCashin({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('cashin',payload).then((resp) => {
            dispatch('fetchCashin');
            if(resp.status==201){
                let message = { 'msg': resp.message }
                dispatch('notification/error', message, { root: true })
            }
            else{
                let message = { 'msg': "Cash in record added successfully" }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Cash in record" }
            dispatch('notification/error', message, { root: true })
        })
    },

      // Edit Cashin
      editCashin({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`cashin/${payload.id}`, payload).then(() => {
            dispatch('fetchCashin');
            let message = { 'msg': "Cash in record updated successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Cash in record" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Delete cashin 
    deleteCashin({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`cashin/${payload.id}`).then(() => {
            dispatch('fetchCashin');
            let message = { 'msg': "Cash in deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Deleting Cash in" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add Cashout
    recordCashout({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('cashout',payload).then((resp) => {
            dispatch('fetchCashout');
            if(resp.status==201){
                let message = { 'msg': resp.message }
                dispatch('notification/error', message, { root: true })
            }
            else{
                let message = { 'msg': "Cash out record added successfully" }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Cash out record" }
            dispatch('notification/error', message, { root: true })
        })
    },

      // Edit Cashout
      editCashout({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`cashout/${payload.id}`, payload).then(() => {
            dispatch('fetchCashout');
            let message = { 'msg': "Cash out record updated successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Cash out record" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Delete cashout 
    deleteCashout({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`cashout/${payload.id}`).then(() => {
            dispatch('fetchCashout');
            let message = { 'msg': "Cash out deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Deleting Cash out" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchCashin({ commit }) {
        axios.get('cashin').then(res => {
            commit('SET_CASHIN', res.data)
        })
    },
    fetchCashout({ commit }) {
        axios.get('cashout').then(res => {
            commit('SET_CASHOUT', res.data)
        })
    },
    fetchTransactions({ commit }, payload) {
        axios.post('transactions', payload).then(res => {
            commit('SET_TRANSACTIONS', res.data)
        })
    },
  
};