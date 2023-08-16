import axios from "axios";
export const state = {
    save_status: false,
    consumable: [],
    consumable_stock: [],
    consumable_usage: [],
    consumable_cart: [],
    consumable_usage_cart: []
};
export const getters = {
    SaveStatus: state => state.save_status,
    Consumables: state => state.consumable.data,
    ConsumablesStock: state => state.consumable_stock.data,
    ConsumablesUsage: state => state.consumable_usage.data,
    ConsumablesCart: state => state.consumable_cart.data,
    ConsumablesUsageCart: state => state.consumable_usage_cart.data

};

export const mutations = {
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_CONSUMABLE(state, newValue) {
        state.consumable = newValue
    },
    SET_CONSUMABLE_STOCK(state, newValue) {
        state.consumable_stock = newValue
    },
    SET_CONSUMABLE_USAGE(state, newValue) {
        state.consumable_usage = newValue
    },
    SET_CONSUMABLE_CART(state, newValue) {
        state.consumable_cart = newValue
    },
    SET_CONSUMABLE_USAGE_CART(state, newValue) {
        state.consumable_usage_cart = newValue
    },
   
};

export const actions = {
    
    fetchConsumables({ commit }, payload) {
        axios.get(`consumables`, payload).then(ec_res => {
            commit('SET_CONSUMABLE', ec_res.data)
        })
    },

    addConsumables({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('consumables',payload).then(() => {
            dispatch('fetchConsumables');
            let message = { 'msg': `Consumable Item ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Consumable Item "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    editConsumables({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`consumables/${payload.id}`, payload).then(() => {
            dispatch('fetchConsumables');
            let message = { 'msg': `Consumable Item ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Consumable Item" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteConsumables({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`consumables/${payload.id}`).then(() => {
            dispatch('fetchConsumables');
            let message = { 'msg': `Consumable Item ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_CONSUMABLE', false)
            let message = { 'msg': "Error in deleting Consumable Item "+error }
            dispatch('notification/error', message, { root: true })
        })
    },


    // ====================== CONSUMABLES STOCKING ========================
    fetchConsumablesStock({ commit }, payload) {
        axios.get(`consumables_stock`, payload).then(ec_res => {
            commit('SET_CONSUMABLE_STOCK', ec_res.data)
        })
    },
    // Consumables Cart
    fetchConsumablesCart({ commit }) {
        axios.get(`consumable_cart`).then(ec_res => {
            commit('SET_CONSUMABLE_CART', ec_res.data)
        })
    },

    addToConsumableCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('add_tocart',payload).then(() => {
            dispatch('fetchConsumablesCart');
            let message = { 'msg': `Consumable Item added to cart successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Consumable Item to cart "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    editConsumableCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`cart_edit/${payload.id}`, payload).then(() => {
            dispatch('fetchConsumablesCart');
            let message = { 'msg': `Consumable Item ${payload.name} updated successfully in cart` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Consumable Item in cart" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteConsumableCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`cart_delete/${payload.id}`).then(() => {
            dispatch('fetchConsumablesCart');
            let message = { 'msg': `Consumable Item ${payload.name} deleted from cart successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_CONSUMABLE_CART', false)
            let message = { 'msg': "Error in deleting Consumable Item from cart "+error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Consumables stocking
    addConsumableStock({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('consumables_stock',payload).then(() => {
            dispatch('fetchConsumablesCart');
            let message = { 'msg': `Consumable Item stock added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Consumable Item to stock "+error }
            dispatch('notification/error', message, { root: true })
        })
    },


    // ====================== CONSUMABLES USAGE ========================
    fetchConsumablesUsage({ commit }, payload) {
        axios.get(`consumables_usage`, payload).then(ec_res => {
            commit('SET_CONSUMABLE_USAGE', ec_res.data)
        })
    },

    // Consumables usage
    addConsumableUsage({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('consumables_usage',payload).then(() => {
            dispatch('fetchConsumablesUsageCart');
            let message = { 'msg': `Consumable Item usage added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Rcording Consumable Item usage "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
   
    editConsumablesUsage({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`consumables_usage/${payload.id}`, payload).then(() => {
            dispatch('fetchConsumables');
            let message = { 'msg': `Consumable Item Usage ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Consumable Item Usage" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteConsumablesUsage({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`consumables_usage/${payload.id}`).then(() => {
            dispatch('fetchConsumablesUsage');
            let message = { 'msg': `Consumable Item Usage${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error in deleting Consumable Item "+error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Consumables Cart
    fetchConsumablesUsageCart({ commit }) {
        axios.get(`consumableusage_cart`).then(ec_res => {
            commit('SET_CONSUMABLE_USAGE_CART', ec_res.data)
        })
    },

    addToConsumableUsageCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('addusage_tocart',payload).then(() => {
            dispatch('fetchConsumablesUsageCart');
            let message = { 'msg': `Consumable Item isage added to cart successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Consumable Item usage to cart "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    editConsumableUsageCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`usagecart_edit/${payload.id}`, payload).then(() => {
            dispatch('fetchConsumablesUsageCart');
            let message = { 'msg': `Consumable Item usage of ${payload.name} updated successfully in cart` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Consumable Item usage in cart" }
            dispatch('notification/error', message, { root: true })
        })
    },
    deleteConsumableUsageCart({ commit,dispatch },payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`usagecart_delete/${payload.id}`).then(() => {
            dispatch('fetchConsumablesUsageCart');
            let message = { 'msg': `Consumable Item usage of ${payload.name} deleted from cart successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false); 
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error in deleting Consumable Item usage from cart "+error }
            dispatch('notification/error', message, { root: true })
        })
    },
    
  
};