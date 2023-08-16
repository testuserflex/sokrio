import axios from "axios";

export const  state = {
    stock_receive_cart:[],
    stock_transfer_cart:[],
    stock_transfers:[],
    save_status:false,
    stock_transfersdetails:[]
};
export const getters = {
    StockTransfers: state => state.stock_transfers.data,
    StockTransfersDetails: state => state.stock_transfersdetails.data,
    StockTransferCart: state => state.stock_transfer_cart.data,
    StockReceiveCart: state => state.stock_receive_cart.data,
    SaveStatus: state => state.save_status,
};
 export const mutations= {

      
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_TRANSFER_RECEIVE_CART(state, newValue) {
        state.stock_receive_cart = newValue
    },
    SET_STOCK_TRANSFERS(state, newValue) {
        state.stock_transfers = newValue
    },
    SET_STOCK_TRANSFERSDETAILS(state, newValue) {
        state.stock_transfersdetails = newValue
    },
    SET_STOCK_TRANSFER_CART(state, newValue) {
        state.stock_transfer_cart = newValue
    },
    
 };
  export const actions={

    // add products to lpo cart
    addToCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('instock_transfer_cart', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            if(response.status==201) {
                let message = { 'msg': response.data.message }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product to Cart" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`instock_transfer_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            if (response.status==200){
                let message = { 'msg': "Product edited successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "Failed to edit Product" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to edit Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    deleteCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`instock_transfer_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            if (response.status==200){
                let message = { 'msg': "Product deleted from cart successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "Failed to delete Product from cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': data.name+' adding failed' }
            dispatch('notification/error', message, { root: true })
        })
    },

    // transfer items
    send({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`instock_transfer`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('store/fetchStoreStock', null, {root:true})
            dispatch('products/fetchProducts', null, {root:true})            
            if (response.status==200){
                let message = { 'msg': "Stock Sent successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "Failed to Transfer" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to Transfer Items" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchCart({ commit }) {
        axios.get('instock_transfer_cart').then(cart => {
            commit('SET_STOCK_TRANSFER_CART', cart.data)
        })
    },
    fetchStockTransfers({ commit }) {
        axios.get('instock_transfer').then(cart => {
            commit('SET_STOCK_TRANSFERS', cart.data)
        })
    },
    fetchStockTransfersDetails({ commit }) {
        axios.get('instocktransfer_details').then(cart => {
            commit('SET_STOCK_TRANSFERSDETAILS', cart.data)
        })
    },

  };


