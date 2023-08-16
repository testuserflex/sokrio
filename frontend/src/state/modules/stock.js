import axios from "axios";

export const state = {
    spoilt: [],
    stock_movements: [],
    supplier_list: [],
    supplier_deposits: [],
    lpo: [],
    lpos: [],
    lpo_cart: [],
    lpo_receive_cart: [],
    lpo_receive_cart_tot: "",
    stock_cart: [],
    short_expiries: [],
    expired_stock: [],
    reconciliation_report: [],
    reconciliation_report_details: [],
    total_shop_cart: "",
    save_status: false,
    purchaseId:'',
    pendingspoilt:[],
    rejectedspoilt:[],
    price_change:[],
    supplier_balances:[],
    supplier_balancedetails:[]
    
};
export const getters = {
    Spoilt: state => state.spoilt.data,
    PendingSpoilt: state => state.pendingspoilt.data,
    RejectedSpoilt: state => state.rejectedspoilt.data,
    Suppliers: state => state.supplier_list.data,
    SupplierDeposits: state => state.supplier_deposits.data,
    TotalShopCart: state => state.total_shop_cart,
    StockCart: state => state.stock_cart.data,
    StockMovement: state => state.stock_movements.data,
    LPOReceiveCart: state => state.lpo_receive_cart.data,
    LPOReceiveCartTot: state => state.lpo_receive_cart_tot,
    LPOCart: state => state.lpo_cart.data,
    LPOs: state => state.lpos.data,
    LPO: state => state.lpo.data,
    ShortExpiries: state => state.short_expiries.data,
    ReconciliationReport: state => state.reconciliation_report.data,
    ReconciliationReportDetails: state => state.reconciliation_report_details.data,
    ExpiredStock: state => state.expired_stock.data,
    SaveStatus: state => state.save_status,
    PurchaseId: state => state.purchaseId,
    PriceChange: state => state.price_change.data,
    SuppliersBalances: state => state.supplier_balances,
    SuppliersBalanceDetails: state => state.supplier_balancedetails.data,

};
export const mutations = {

    SET_STOCK_MOVEMENTS(state, newValue) {
        state.stock_movements = newValue
    },
    SET_SPOILT(state, newValue) {
        state.spoilt = newValue
    },
    SET_PENDINGSPOILT(state, newValue) {
        state.pendingspoilt = newValue
    },
    SET_REJECTEDSPOILT(state, newValue) {
        state.rejectedspoilt = newValue
    },
    SET_RECONCILIATION_REPOPRT(state, newValue) {
        state.reconciliation_report = newValue
    },
    SET_RECONCILIATION_REPOPRT_DETAILS(state, newValue) {
        state.reconciliation_report_details = newValue
    },
    SET_SHORT_EXPIRIES(state, newValue) {
        state.short_expiries = newValue
    },
    SET_EXPIRED(state, newValue) {
        state.expired_stock = newValue
    },
    SET_SUPPLIERS(state, newValue) {
        state.supplier_list = newValue
    },
    SET_SUPPLIER_DEPOSITS(state, newValue) {
        state.supplier_deposits = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_LPO_CART(state, newValue) {
        state.lpo_cart = newValue
    },
    SET_LPO_RECEIVE_CART(state, newValue) {
        state.lpo_receive_cart = newValue
    },
    SET_LPO_RECEIVE_CART_TOT(state, newValue) {
        state.lpo_receive_cart_tot = newValue
    },
    SET_LPOS(state, newValue) {
        state.lpos = newValue
    },
    SET_LPO(state, newValue) {
        state.lpo = newValue
    },
    SET_STOCK_CART(state, newValue) {
        state.stock_cart = newValue
    },
    SET_TOTAL_SHOP_CART(state, newValue) {
        state.total_shop_cart = newValue
    },
    SET_PURCHASES_ID(state, newValue) {
        state.purchaseId = newValue
    },
    SET_PRICE_CHANGE(state, newValue) {
        state.price_change = newValue
    },
    SET_SUPPLIER_BALANCES(state, newValue) {
        state.supplier_balances = newValue
    },
    SET_SUPPLIER_BALANCEDETAILS(state, newValue) {
        state.supplier_balancedetails = newValue
    },
};
export const actions = {
    addSpoilt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('spoilt_stock', payload).then(() => {
            dispatch('fetchSpoilt');
            let message = { 'msg': `${payload.quantity} items of Product ${payload.name} await confirmation as spoilt ` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Spoilt Stock " }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Confirm Spoilt Stock
    ConfirmSpoilt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('confirmspoilt_stock', payload).then(() => {
            dispatch('fetchPendingSpoilt');
            let message = { 'msg': `item ${payload.name} spoilt stock record has been successfully confirmed` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Confirming Spoilt Stock " }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Reject Spoilt Stock
    RejectSpoilt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('rejectspoilt_stock', payload).then(() => {
            dispatch('fetchPendingSpoilt');
            let message = { 'msg': `item ${payload.name} spoilt stock record has been successfully rejected` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Rejecting Spoilt Stock " }
            dispatch('notification/error', message, { root: true })
        })
    },

    deleteSpoilt({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`spoilt_stock/${payload.id}`).then(() => {
            dispatch('fetchSpoilt');
            let message = { 'msg': `Spoilt stock record deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    fetchSpoilt({ commit }) {
        axios.get('spoilt_stock').then(ec_res => {
            commit('SET_SPOILT', ec_res.data)
        })
    },
    // Fetch Pending Spoilt Stock
    fetchPendingSpoilt({ commit }) {
        axios.get('pending').then(ec_res => {
            commit('SET_PENDINGSPOILT', ec_res.data)
        })
    },
    // Fetch Rejected Spoilt Stock
    fetchRejectedSpoilt({ commit }) {
        axios.get('rejected').then(ec_res => {
            commit('SET_REJECTEDSPOILT', ec_res.data)
        })
    },
    //   ADD SUPPLIER
    addSupplier({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('suppliers', payload).then(() => {
            dispatch('fetchSuppliers');
            let message = { 'msg': `Supplier ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Supplier " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Supplier
    editSupplier({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`suppliers/${payload.id}`, payload).then(() => {
            dispatch('fetchSuppliers');
            let message = { 'msg': `Supplier ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Supplier" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Supplier
    deleteSupplier({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`suppliers/${payload.id}`).then(() => {
            dispatch('fetchSuppliers');
            let message = { 'msg': `Supplier ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add products to shop cart
    addToCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('shop_purchase_cart', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            if (response.status != 200) {
                let message = { 'msg': "Failed to add Product to cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`shop_purchase_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
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

    deleteCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`shop_purchase_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            if (response.status == 200) {
                let message = { 'msg': "Product deleted from cart successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete Product from cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': data.name + ' deleting failed' }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Process purchase and print
    savePurchasePrint({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`shop_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            if (response.status == 200) {
                let message = { 'msg': "Purchase Recorded successfully" }
                dispatch('notification/success', message, { root: true })
                commit('SET_PURCHASES_ID', response.data.id)
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to record Purchase" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            let message = { 'msg': "Failed to record Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Process purchase and Exit
    savePurchaseExit({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`shop_purchases`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            if (response.status == 200) {
                let message = { 'msg': "Purchase Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to record Purchase" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotShopCart')
            let message = { 'msg': "Failed to record Purchase" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // remove expired stock
    removeExpired({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('mark_as_expired', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchExpiredStock')
            if (response.status == 200) {
                let message = { 'msg': "Product successfully marked as expired" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Operation was unsuccessful" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to mark Product as Expired" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // LPO
    // add products to lpo cart
    addToCartLPO({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('lpo_cart', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPOCart')
            if (response.status == 201) {
                let message = { 'msg': "Item already exists in cart" }
                dispatch('notification/error', message, { root: true })
            } else if (response.status != 201 && response.status != 200) {
                let message = { 'msg': "Error in adding to cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product to Cart" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editCartLPO({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`lpo_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPOCart')
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

    deleteCartLPO({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`lpo_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPOCart')
            if (response.status == 200) {
                let message = { 'msg': "Product deleted from cart successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete Product from cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': 'Failed to delete Product from cart' }
            dispatch('notification/error', message, { root: true })
        })
    },


    // Add LPO Product
    addLPOProduct({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('lpo_product', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPODetails', data.id)
            if (response.status == 200) {
                let message = { 'msg': "LPO Product added successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to add LPO Product" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': 'LPO Product adding failed' }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Delete LPO
    deleteAllLPO({ dispatch, commit }, id) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`lpo_delete/${id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPOs')
            if (response.status == 200) {
                let message = { 'msg': "LPO deleted successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete LPO" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': 'Failed to delete LPO' }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Process purchase order
    savePurchaseOrder({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`lpos`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPOCart')
            if (response.status == 200) {
                let message = { 'msg': "Purchase Order Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to record Purchase Order" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to record Purchase Order" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editLPO({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`lpos/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPODetails', data.lpo)
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

    deleteLPO({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`lpos/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPODetails', data.lpo)
            if (response.status == 200) {
                let message = { 'msg': "Product deleted successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to delete Product" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to edit Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    LPOPayment({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('lpo_payment', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPODetails', response.data.id)
            if (response.status == 200) {
                let message = { 'msg': "Payment added successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to add Payment" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Payment" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Receiving lpo items
    // LPO
    // add products to lpo cart
    addToCartLpoReceive({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('lpo_receive', data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch("fetchLpoReceiveCart", data.lpoId)
            dispatch('fetchLpoReceiveCartTot', data.lpoId)
            if (response.status == 200) {
                let message = { 'msg': "Product added to cart successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Item already exists in cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product to Cart" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editCartLpoReceive({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`lpo_receive/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch("fetchLpoReceiveCart", data.lpoId)
            dispatch('fetchLpoReceiveCartTot', data.lpoId)
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

    deleteCartLpoReceive({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.delete(`lpo_receive/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch("fetchLpoReceiveCart", data.lpoId)
            dispatch('fetchLpoReceiveCartTot', data.lpoId)
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
    // Process purchase order
    ReceivePurchaseOrder({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post(`lpo_purchase`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchLPODetails', data.lpo)
            dispatch("fetchLpoReceiveCart", data.lpo)
            dispatch('fetchLpoReceiveCartTot', data.lpo)
            if (response.status == 200) {
                let message = { 'msg': "Purchase Order Processed successfully" }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                let message = { 'msg': "Failed to Processing Purchase Order" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to record Purchase Order" }
            dispatch('notification/error', message, { root: true })
        })
    },
    //  edit stock buying price
    editBPrice({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`update_buyingPrice/${payload.id}`, payload).then(() => {
            dispatch('products/viewProducts', null, { root: true });
            let message = { 'msg': `Buying Price Amount  updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Buying Price" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add Supplier deposit
    addSupplierDeposit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('supplier_deposit', payload).then(() => {
            dispatch('fetchSupplierDeposits');
            let message = { 'msg': ` Supplier deposit of ${payload.amount}  recorded successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Supplier Deposit " }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Supplier Deposit
    editSupplierDeposit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`supplier_deposit/${payload.id}`, payload).then(() => {
            dispatch('fetchSupplierDeposits');
            let message = { 'msg': `Supplier Deposit Updated Successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Supplier Deposit " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // delete Supplier deposit
    deleteSupplierDeposit({ commit, dispatch }, payload) {
        ``
        commit('SET_SAVE_STATUS', true);
        axios.delete(`supplier_deposit/${payload.id}`).then(() => {
            dispatch('fetchSupplierDeposits');
            let message = { 'msg': `Supplier Deposit  record deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    
    // Edit Stock Details
    editStock({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`edit_stock/${data.id}`, data).then(response => {
            if (response.status == 200) {
                commit('SET_SAVE_STATUS', false)
                dispatch('products/viewProducts', null, { root: true });
                let message = { 'msg': `Stock details updated successfully` }
                dispatch('notification/success', message, { root: true })
            } else if (response.status == 201) {
                commit('SET_SAVE_STATUS', false)
                let message = { 'msg': "Failed to update stock details" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to update stock details" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Fetch Supplier Deposits
    fetchSupplierDeposits({ commit }) {
        axios.get('supplier_deposit').then(ec_res => {
            commit('SET_SUPPLIER_DEPOSITS', ec_res.data)
        })
    },

    fetchSuppliers({ commit }) {
        axios.get(`suppliers`).then(ec_res => {
            commit('SET_SUPPLIERS', ec_res.data)
        })
    },

    fetchCart({ commit }) {
        axios.get('shop_purchase_cart').then(cart => {
            commit('SET_STOCK_CART', cart.data)
        })
    },

    fetchTotShopCart({ commit }) {
        axios.get('total_shop_cart').then(total => {
            commit('SET_TOTAL_SHOP_CART', total.data)
        })
    },

    fetchLPOCart({ commit }) {
        axios.get('lpo_cart').then(cart => {
            commit('SET_LPO_CART', cart.data)
        })
    },

    fetchLPOs({ commit }) {
        axios.get('lpos').then(cart => {
            commit('SET_LPOS', cart.data)
        })
    },
    fetchLPODetails({ commit }, id) {
        axios.get(`lpos/${id}`).then(cart => {
            commit('SET_LPO', cart.data)
        })
    },
    fetchLpoReceiveCart({ commit }, id) {
        axios.get(`lpo_receive/${id}`).then(cart => {
            commit('SET_LPO_RECEIVE_CART', cart.data)
        })
    },
    fetchLpoReceiveCartTot({ commit }, id) {
        axios.get(`lpo_receive_tot/${id}`).then(cart => {
            commit('SET_LPO_RECEIVE_CART_TOT', cart.data)
        })
    },
    fetchShortExpiries({ commit }) {
        axios.get('short_expiries').then(total => {
            commit('SET_SHORT_EXPIRIES', total.data)
        })
    },
    fetchExpiredStock({ commit }) {
        axios.get('expired').then(total => {
            commit('SET_EXPIRED', total.data)
        })
    },
    fetchReconciliationReport({ commit }) {
        axios.get('reconcile').then(total => {
            commit('SET_RECONCILIATION_REPOPRT', total.data)
        })
    },
    fetchReconciliationReportDetails({ commit }, id) {
        axios.get(`reconcile/${id}`).then(total => {
            commit('SET_RECONCILIATION_REPOPRT_DETAILS', total.data)
        })
    },

    fetchStockMovements({ commit }, id) {
        axios.get(`stock_movement/${id}`).then(cart => {
            commit('SET_STOCK_MOVEMENTS', cart.data)
        })
    },

    // Fetch Price Changes
    fetchPriceChanges({ commit }) {
        axios.get(`shopprice_change`).then(res => {
            commit('SET_PRICE_CHANGE', res.data)
        })
    },

    // supplier balances
    fetchSuppliersBalances({ commit }) {
        axios.get('supplier_balances').then(ec_res => {
            commit('SET_SUPPLIER_BALANCES', ec_res.data)
        })
    },

    // supplier balances details
    fetchSuppliersBalanceDetails({ commit }, id) {
        axios.get(`supplier_balancedetails/${id}`).then(ec_res => {
            commit('SET_SUPPLIER_BALANCEDETAILS', ec_res.data)
        })
    },

};