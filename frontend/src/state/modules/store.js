import axios from "axios";

export const  state = {
    supplier_list:[],
    store_stock:[],
    store_pdts:[],
    store_cart:[],
    total_store_cart:"",
    save_status:false,
    purchaseId:'',
    price_change:[],
    filter_status:false
};
export const getters = {
    StoreStock: state => state.store_stock.data,
    StorePdts: state => state.store_pdts.data,
    Suppliers: state => state.supplier_list.data,
    TotalStoreCart: state => state.total_store_cart,
    StoreCart: state => state.store_cart.data,
    SaveStatus: state => state.save_status,
    PurchaseId: state => state.purchaseId,
    FilterStatus: state => state.filter_status,
    PriceChange: state => state.price_change.data,

};
export const mutations= {

    SET_STORE_STOCK(state,newValue){
        state.store_stock = newValue
    },
    SET_STORE_PDTS(state,newValue){
        state.store_pdts = newValue
    },
    SET_SUPPLIERS(state,newValue){
        state.supplier_list = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },

    SET_STORE_CART(state, newValue) {
        state.store_cart = newValue
    },
    SET_TOTAL_STORE_CART(state, newValue) {
        state.total_store_cart = newValue
    },
    SET_PURCHASES_ID(state, newValue) {
        state.purchaseId = newValue
    },
    SET_PRICE_CHANGE(state, newValue) {
        state.price_change = newValue
    },
    SET_FILTER_STATUS(state, newValue) {
        state.filter_status = newValue
    },

};
export const actions={

    // Process purchase and print
    savePurchasePrint({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`store_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
            if (response.status==200){
                let message = { 'msg': "Purchase Recorded successfully" }
                dispatch('notification/success', message, { root: true })
                commit('SET_PURCHASES_ID', response.data.id)
            }else if(response.status==201) {
                let message = { 'msg': "Failed to record Purchase" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
            let message = { 'msg': "Failed to record Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Process purchase and exit
    savePurchaseExit({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`store_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
            if (response.status==200){
                let message = { 'msg': "Purchase Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "Failed to record Purchase" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
            let message = { 'msg': "Failed to record Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add products to store cart
    addToStoreCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('store_purchase_cart', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
            if(response.status != 200) {
                let message = { 'msg': "Failed to add Product to cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editStoreCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`store_purchase_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
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

    deleteStoreCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`store_purchase_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchStoreCart')
            dispatch('fetchTotStoreCart')
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

    // remove expired stock
    removeExpired({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('mark_as_expired', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchExpiredStock')
            if (response.status==200){
                let message = { 'msg': "Product successfully marked as expired" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "Operation was unsuccessful" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to mark Product as Expired" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Store roconciliation
    addQty({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`reconcile/${payload.id}`, payload).then(() => {
            dispatch('fetchStoreStock');
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
        })
    },
    SaveReconcile({ commit,dispatch }) {
        commit('SET_SAVE_STATUS', true);
        axios.post(`save_reconcile`,{type:1}).then(() => {
            dispatch('fetchStoreStock');
            let message = { 'msg': `Stock Reconciled successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Reconciling Stock" }
            dispatch('notification/error', message, { root: true })
        })
    },
    editBPrice({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`updateStoreBPrice/${payload.id}`, payload).then(() => {
            dispatch('fetchStoreStock');
            let message = { 'msg': `Buying Price Amount  updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Buying Price" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchSuppliers({ commit }) {
        axios.get(`suppliers`).then(ec_res => {
            commit('SET_SUPPLIERS', ec_res.data)
        })
    },

    fetchStoreCart({ commit }) {
        axios.get('store_purchase_cart').then(cart => {
            commit('SET_STORE_CART', cart.data)
        })
    },

    fetchTotStoreCart({ commit }) {
        axios.get('total_store_cart').then(total => {
            commit('SET_TOTAL_STORE_CART', total.data)
        })
    },

    fetchStoreStock({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('store_stock', payload).then(total => {
            commit('SET_STORE_STOCK', total.data)
            commit('SET_FILTER_STATUS', false)
        })
    },

    fetchStorePdts({ commit }) {
        axios.get(`store`).then(total => {
            commit('SET_STORE_PDTS', total.data)
        })
    },

    import_products({commit, dispatch}){
        commit('SET_SAVE_STATUS', true);
        axios.post('imports').then((res) => {
            if (res.data.status == 200) {
                let message = { 'msg': res.data.message }
                dispatch('notification/success', message, { root: true })
                commit('SET_SAVE_STATUS', false);
            }
            else if (res.data.status == 201) {
                let message = { 'msg': res.data.message }
                dispatch('notification/error', message, { root: true })
                commit('SET_SAVE_STATUS', false);
            }
        })
        .catch(() => {
            let message = { 'msg': 'Importation failed, please try again later' }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        });
    },

    // Fetch Price Changes
    fetchPriceChanges({ commit }) {
        axios.get(`storeprice_change`).then(res => {
            commit('SET_PRICE_CHANGE', res.data)
        })
    },



};


