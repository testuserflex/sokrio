import axios from "axios";

export const state = {
    cart: [],
    products: "",
    total_cart: "",
    save_status: false,
    sale_save_status: false,
    invoices: [],
    invoice: [],

};
export const getters = {
    TotalCart: state => state.total_cart,
    InvoiceCart: state => state.cart.data,
    SaveStatus: state => state.save_status,
    SaleSaveStatus: state => state.sale_save_status,
    Invoices: state => state.invoices.data,
    Invoice: state => state.invoice.data,

};
export const mutations = {
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_INVOICE_SAVE_STATUS(state, newValue) {
        state.sale_save_status = newValue
    },
    SET_CART(state, newValue) {
        state.cart = newValue
    },

    SET_TOTAL_CART(state, newValue) {
        state.total_cart = newValue
    },
    SET_INVOICES(state, newValue) {
        state.invoices = newValue
    },
    SET_INVOICE(state, newValue) {
        state.invoice = newValue
    },


};
export const actions = {

    // add products to invoice cart
    addToCart({ dispatch, commit }, data) {
        commit('SET_INVOICE_SAVE_STATUS', true)
        axios.post('invoice_cart', data).then(() => {
            commit('SET_INVOICE_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
        }).catch(() => {
            commit('SET_INVOICE_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // edit invoice cart
    editCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`invoice_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            if (response.status == 200) {
                let message = { 'msg': "Product edited successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to edit Product" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to edit Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // delete items from invoice cart
    deleteCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`invoice_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            if (response.status == 200) {
                let message = { 'msg': "Product deleted from cart successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete Product from cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': data.name + ' adding failed' }
            dispatch('notification/error', message, { root: true })
        })
    },



    // Process Invoice
    saveInvoice({ dispatch, commit }, data) {
        commit('SET_INVOICE_SAVE_STATUS', true)
        axios.post(`invoices`, data).then(response => {
            commit('SET_INVOICE_SAVE_STATUS', false)

            if (response.status == 200) {
                let message = { 'msg': "Invoice Recorded successfully" }
                dispatch('notification/success', message, { root: true })
                dispatch('fetchCart')
                dispatch('fetchTotCart')
            } else if (response.status == 202) {
                let message = { 'msg': "Failed to record Sale" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_INVOICE_SAVE_STATUS', false)
            let message = { 'msg': "Failed to record an Invoice" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // delete Invoice
    deleteInvoice({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`invoices/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchInvoices')
            if (response.status == 200) {
                let message = { 'msg': "Invoice Deleted successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete Invoice" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': data.name + ' Deleting Failed' }
            dispatch('notification/error', message, { root: true })
        })
    },
    // get invoice cart
    fetchCart({ commit }) {
        axios.get('invoice_cart').then(cart => {
            commit('SET_CART', cart.data)
        })
    },
    // 

    // get invoice cart Total
    fetchTotCart({ commit }) {
        axios.get('total_invoice_cart').then(total => {
            commit('SET_TOTAL_CART', total.data)
        })
    },
    // get invoices
    fetchInvoices({ commit }) {
        axios.get('invoices').then(cart => {
            commit('SET_INVOICES', cart.data)
        })
    },
    // get a single invoice
    fetchInvoice({ commit }, data) {
        axios.get(`invoices/${data.id}`).then(cart => {
            commit('SET_INVOICE', cart.data)
        })
    },

};