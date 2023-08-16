import axios from "axios";

export const state = {
    customers: [],
    customer_balances: {},
    customer_deposits: [],
    customer_sales: [],
    save_status: false,
    customer_balancedetails:[],
    depositId:''
};
export const getters = {
    Customers: state => state.customers.data,
    CustomerBalances: state => state.customer_balances,
    CustomerBalanceDetails: state => state.customer_balancedetails.data,
    CustomerDeposits: state => state.customer_deposits.data,
    CustomerSales: state => state.customer_sales.data,
    SaveStatus: state => state.save_status,
    DepositID: state => state.depositId,
};
export const mutations = {
    SET_CUSTOMERS(state, newValue) {
        state.customers = newValue
    },
    SET_CUSTOMER_BALANCES(state, newValue) {
        state.customer_balances = newValue
    },
    SET_CUSTOMER_DEPOSITS(state, newValue) {
        state.customer_deposits = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_CUSTOMER_BALANCEDETAILS(state, newValue) {
        state.customer_balancedetails = newValue
    },
    SET_CUSTOMER_SALES(state, newValue) {
        state.customer_sales = newValue
    },
    SET_DEPOSIT_ID(state, newValue) { 
        state.depositId = newValue
    },
};
export const actions = {
    addCustomer({ commit, dispatch }, payload) {        
        commit('SET_SAVE_STATUS', true);
        axios.post('customers', payload).then(() => {
            dispatch('fetchCustomers');            
            // let message = { 'msg': ` Customer ${payload.name}  with Contact ${payload.contact} added successfully` }
            let message = { 'msg': ` Customer added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Customer " }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteCustomer({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`customers/${payload.id}`).then(() => {
            dispatch('fetchCustomers');
            let message = { 'msg': `Customer  record deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit customer
    editCustomer({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`customers/${payload.id}`, payload).then(() => {
            dispatch('fetchCustomers');
            let message = { 'msg': `Details of Customer ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Customer Details " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // add customer deposit
    addCustomerDeposit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('customer_deposit', payload).then((response) => {
            if (response.data.status==200){
                commit('SET_DEPOSIT_ID', response.data.id)
                let message = { 'msg': ` Customer deposit of ${payload.amount}  recorded successfully` }
                dispatch('notification/success', message, { root: true });                
            }else if(response.data.status==202) {
                let message = { 'msg': "Failed to record Deposit" }
                dispatch('notification/error', message, { root: true })
            }
            dispatch('fetchCustomerDeposits');            
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Customer Deposit " }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit customer Deposit
    editCustomerDeposit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`customer_deposit/${payload.id}`, payload).then(() => {
            dispatch('fetchCustomerDeposits');
            let message = { 'msg': `Customer Deposit Updated Successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Customer Depsoit " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // delete customer deposit
    deleteCustomerDeposit({ commit, dispatch }, payload) {
        ``
        commit('SET_SAVE_STATUS', true);
        axios.delete(`customer_deposit/${payload.id}`).then(() => {
            dispatch('fetchCustomerDeposits');
            let message = { 'msg': `Customer Deposit  record deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Fetch customer Deposits
    fetchCustomerDeposits({ commit }) {
        axios.get('customer_deposit').then(ec_res => {
            commit('SET_CUSTOMER_DEPOSITS', ec_res.data)
        })
    },
    // Fetch customers

    fetchCustomers({ commit }) {
        axios.get('customers').then(ec_res => {
            commit('SET_CUSTOMERS', ec_res.data)
        })
    },
    // customer balances
    fetchCustomerBalances({ commit }) {
        axios.get('customer_balances').then(ec_res => {
            commit('SET_CUSTOMER_BALANCES', ec_res.data)
        })
    },

    // customer balances details
    fetchCustomerBalanceDetails({ commit }, id) {
        axios.get(`customer_balancedetails/${id}`).then(ec_res => {
            commit('SET_CUSTOMER_BALANCEDETAILS', ec_res.data)
        })
    },

    // customer balances details
    fetchCustomerSales({ commit }, payload) {
        axios.post('customer_sales', payload).then(ec_res => {
            commit('SET_CUSTOMER_SALES', ec_res.data)
        })
    },



};