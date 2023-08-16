import axios from "axios";
import router from '../../router/index';

export const state = {
    sales: [],
    untaken_orders: [],
    debtors: [],
    save_status: false,
    sales_report: [],
    filter_status: false,
    sales_analysis: [],
    imported_debtors:[],
    saleId:''
};
export const getters = {
    UntakenOrders: state => state.untaken_orders.data,
    Sales: state => state.sales.data,
    SalesReport: state => state.sales_report.data,
    Debtors: state => state.debtors.data,
    SalesId: state => state.saleId,
    ImportedDebtors: state => state.imported_debtors.data,
    SaveStatus: state => state.save_status,
    filterStatus: state => state.filter_status,
    SalesAnalysis: state => state.sales_analysis.data,
};
export const mutations = {
    SET_SALES(state, newValue) {
        state.sales = newValue
    },
    SET_SALES_REPORT(state, newValue) {
        state.sales_report = newValue
    },
    SET_UNTAKEN(state, newValue) {
        state.untaken_orders = newValue
    },
    SET_DEBTORS(state, newValue) {
        state.debtors = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_FILTER_STATUS(state, newValue) {
        state.filter_status = newValue
    },
    SET_SALES_ANALYSIS(state, newValue) {
        state.sales_analysis = newValue
    },
    SET_IMPORTED_DEBTORS(state, newValue) {
        state.imported_debtors = newValue
    },
    SET_SALE_ID(state, newValue) { 
        state.saleId = newValue
    },

};
export const actions = {
    editAmtPaidMode({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`update_amt_paid/${payload.id}`, payload).then((response) => {
            if (response.status == 201) {
                let message = { 'msg': response.data.message }
                dispatch('notification/error', message, { root: true })

            } else {
                let message = { 'msg': `Sale Amount Paid updated successfully` }
                dispatch('notification/success', message, { root: true })

            }
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Paid Amount" }
            dispatch('notification/error', message, { root: true })
        })
    },
    editSale({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`sales/${payload.id}`, payload).then(() => {
            commit('SET_SAVE_STATUS', false);
            router.push('/sales/order').catch(() => {});
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Returning Sale" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteSale({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`sales/${payload.id}`).then(() => {
            let message = { 'msg': `Sale Deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Deleting Sale" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Clear debt
    ClearDebt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`pay_debt/${payload.id}`, payload).then(() => {
            let message = { 'msg': `Debt of ${payload.paid} Cleared successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Clearing Debt" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Clear debt
    ClearDebtPrint({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`pay_debt/${payload.id}`, payload).then((res) => {
            if (res.data.status==200){
                commit('SET_SAVE_STATUS', false);
                commit('SET_SALE_ID', res.data.id)                
            }else{
                commit('SET_SAVE_STATUS', false);
                let message = { 'msg': "Failed to record Payment" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Clearing Debt" }
            dispatch('notification/error', message, { root: true })
        })
    },


    // Clear debt
    ClearImportedDebt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`clear_importeddebtors/${payload.id}`, payload).then(() => {
            dispatch('fetchImportedDebtors');
            let message = { 'msg': `Debt of ${payload.paid} Cleared successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Clearing Debt" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchDebtors({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('debtors',payload).then(total => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_DEBTORS', total.data)
        })
    },
    fetchImportedDebtors({ commit }) {
        axios.get('imported_debtors').then(total => {
            commit('SET_IMPORTED_DEBTORS', total.data)
        })
    },
    fetchSales({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('sales_data', payload).then(total => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALES', total.data)
        })
    },
    fetchSalesReport({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('sales_report', payload).then(res => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALES_REPORT', res.data)
        })
    },
    fetchUnTakenOrders({ commit }) {
        axios.get(`untaken_orders`).then(total => {
            commit('SET_UNTAKEN', total.data)
        })
    },

    fetchSalesAnalysis({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('sales_analysis', payload).then(res => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALES_ANALYSIS', res.data)
        })
    },

};