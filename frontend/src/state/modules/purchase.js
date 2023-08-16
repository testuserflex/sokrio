import axios from "axios";

export const state = {
    purchases: [],
    store_purchases: [],
    store_purchases_report: [],
    purchases_report: [],
    creditors: [],
    imported_creditors:[],
    save_status: false,
    purchase_payments: [],
    purchases_receipt:'',
    purchase_returns:[],
};
export const getters = {
    Purchases: state => state.purchases.data,
    StorePurchases: state => state.store_purchases.data,
    StorePurchasesReport: state => state.store_purchases_report.data,
    PurchasesReport: state => state.purchases_report.data,
    Creditors: state => state.creditors.data,
    ImportedCreditors: state => state.imported_creditors.data,
    SaveStatus: state => state.save_status,
    PurchasePayments: state => state.purchase_payments.data,
    PurchasesReceipt: state => state.purchases_receipt.data,
    PurchasesReturn: state => state.purchase_returns.data,

};
export const mutations = {
    SET_PURCHASES(state, newValue) {
        state.purchases = newValue
    },
    SET_STORE_PURCHASES(state, newValue) {
        state.store_purchases = newValue
    },
    SET_STORE_PURCHASES_REPORT(state, newValue) {
        state.store_purchases_report = newValue
    },
    SET_PURCHASES_REPORT(state, newValue) {
        state.purchases_report = newValue
    },
    SET_CREDITORS(state, newValue) {
        state.creditors = newValue
    },
    SET_IMPORTED_CREDITORS(state, newValue) {
        state.imported_creditors = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_PURCHASE_PAYMENTS(state, newValue) {
        state.purchase_payments = newValue
    },
    SET_PURCHASE_RECEIPT(state, newValue) {
        state.purchases_receipt = newValue
    },
    SET_PURCHASE_RETURNS(state, newValue) {
        state.purchase_returns = newValue
    }   

};
export const actions = {
    editAmtPaidMode({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`update_purchase_amount/${payload.id}`, payload).then((response) => {
            if (response.status == 201) {
                let message = { 'msg': response.data.message }
                dispatch('notification/error', message, { root: true })

            } else {
                dispatch('fetchShopPurchases');
                dispatch('fetchStorePurchases');
                let message = { 'msg': `Purchase Amount Paid updated successfully` }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Paid Amount" }
            dispatch('notification/error', message, { root: true })
        })
    },
    editPurchase({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`shop_purchases/${payload.id}`, payload).then((response) => {
            if (response.status == 200) {
                dispatch('fetchShopPurchases');
                dispatch('fetchStorePurchases');
                let message = { 'msg': `Purchase Returned successfully, Go to Add Stock to Edit` }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': response.data.message }
                dispatch('notification/error', message, { root: true })
            }

            // let message = { 'msg': `Purchase Returned successfully, Go to Add Stock to Edit` }
            // dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Returning Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deletePurchase({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`shop_purchases/${payload.id}`).then(() => {
            dispatch('fetchShopPurchases');
            let message = { 'msg': `Purchase Deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Deleting Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Clear credit
    ClearCredit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`pay_credit/${payload.id}`, payload).then(() => {
            dispatch('fetchCreditors');
            let message = { 'msg': `Credit of ${payload.paid} Cleared successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Clearing Credit" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Clear credit
    ClearImportedCredit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`pay_imported_credit/${payload.id}`, payload).then(() => {
            dispatch('fetchImportedCreditors');
            let message = { 'msg': `Credit of ${payload.paid} Cleared successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Clearing Credit" }
            dispatch('notification/error', message, { root: true })
        })
    },


    fetchCreditors({ commit }) {
        axios.get('creditors').then(total => {
            commit('SET_CREDITORS', total.data)
        })
    },
    fetchImportedCreditors({ commit }) {
        axios.get('imported_creditors').then(total => {
            commit('SET_IMPORTED_CREDITORS', total.data)
        })
    },
    fetchShopPurchases({ commit }) {
        axios.get(`shop_purchases`).then(total => {
            commit('SET_PURCHASES', total.data)
        })
    },
    fetchStorePurchases({ commit }) {
        axios.get(`store_purchases`).then(total => {
            commit('SET_STORE_PURCHASES', total.data)
        })
    },
    fetchStorePurchasesReport({ commit }, payload) {
        axios.post('store_purchases_report',payload).then(total => {
            commit('SET_STORE_PURCHASES_REPORT', total.data)
        })
    },
    fetchPurchasesReport({ commit },payload) {
        axios.post('purchases_report',payload).then(total => {
            commit('SET_PURCHASES_REPORT', total.data)
        })
    },
    fetchPurchasePayments({ commit }) {
        axios.get('purchase_payments').then(res => {
            commit('SET_PURCHASE_PAYMENTS', res.data)
        })
    },
    PrintPurchaseReceipt({commit},Id){
        axios.get(`purchase_print_receipt/${Id}`).then(res =>{
          commit("SET_PURCHASE_RECEIPT",res)
        })
    
    },
    returnStorePurchase({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`return_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStorePurchases')
            if (response.data.status==200){
                let message = { 'msg': "Store Purchase Return Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==202) {
                let message = { 'msg': "Failed to record Store Purchase Return" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to record Store Purchase Return" }
            dispatch('notification/error', message, { root: true })
        })
    },
    returnShopPurchase({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`return_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchShopPurchases')
            if (response.data.status==200){
                let message = { 'msg': "Shop Purchase Return Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==202) {
                let message = { 'msg': "Failed to record Shop Purchase Return" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to record Shop Purchase Return" }
            dispatch('notification/error', message, { root: true })
        })
    },
    fetchReturns({ commit }) {
        axios.get('fetch_purchase_returns').then(total => {
            commit('SET_PURCHASE_RETURNS', total.data)
        })
    },

};