import axios from "axios";

export const  state = {
    cart:[],
    products:"",
    total_cart:"",
    held_sales: [],
    hold_details: [],
    all_held_sales: [],
    save_status:false,
    sale_save_status:false,
    saleId:'',
    // sale_receipt:'',
    sale_analysis:[],
    sale_return:[],
    sale_payments:[],
    sale_creditpayments:[],
    filter_status:false,
    pending_orders:[],
    ready_orders:[],
    monthlysale_analysis:[],
    monthlyprofit_analysis:[],
    sales_details:[],
};
export const getters = {
    Products: state => state.products.data,
    TotalCart: state => state.total_cart,
    Cart: state => state.cart.data,
    SaleHeld: state => state.held_sales.data,
    HoldInvoice: state => state.hold_details.data,
    AllSaleHeld: state => state.all_held_sales.data,
    SaveStatus: state => state.save_status,
    SaleSaveStatus: state => state.sale_save_status,
    SalesId: state => state.saleId,
    // SalesReceipt: state => state.sale_receipt.data,
    SalesAnalysis: state => state.sale_analysis.data,
    SalesReturn: state => state.sale_return.data,
    SalePayments: state => state.sale_payments.data,
    SaleCreditPayments: state => state.sale_creditpayments.data,
    PendingOrders: state => state.pending_orders.data,
    ReadyOrders: state => state.ready_orders.data,
    filterStatus: state => state.filter_status,
    MonthlySalesAnalysis: state => state.monthlysale_analysis,
    MonthlyProfitsAnalysis: state => state.monthlyprofit_analysis,
    SaleDetails: state => state.sales_details.data,

};
 export const mutations= {
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_SALESAVE_STATUS(state, newValue) {
        state.sale_save_status = newValue
    },
    SET_CART(state, newValue) {
        state.cart = newValue
    },
    SET_HOLD(state, newValue) {
        state.held_sales = newValue
    },
    SET_HOLD_DETAILS(state, newValue) {
        state.hold_details = newValue
    },
    SET_ALL_HOLD(state, newValue) {
        state.all_held_sales = newValue
    },
    SET_ITEMS(state, newValue) {
        state.products = newValue
    },
    SET_TOTAL_CART(state, newValue) {
        state.total_cart = newValue
    },
    SET_SALE_ID(state, newValue) { 
        state.saleId = newValue
    },
    // SET_SALE_RECEIPT(state, newValue) { 
    //     state.sale_receipt = newValue
    // },
    SET_SALE_ANALYSIS(state, newValue){
        state.sale_analysis = newValue
    },
    SET_SALE_RETURNS(state, newValue){
        state.sale_return = newValue
    },

    SET_SALE_PAYMENTS(state, newValue){
        state.sale_payments = newValue
    },

    SET_SALE_CREDITPAYMENTS(state, newValue){
        state.sale_creditpayments = newValue
    },

    SET_FILTER_STATUS(state, newValue) {
        state.filter_status = newValue
    },
    SET_PENDING_ORDERS(state, newValue) {
        state.pending_orders = newValue
    },
    SET_READY_ORDERS(state, newValue) {
        state.ready_orders = newValue
    },
    SET_MONTHLYSALE_ANALYSIS(state, newValue){
        state.monthlysale_analysis = newValue
    },
    SET_MONTHLYPROFIT_ANALYSIS(state, newValue){
        state.monthlyprofit_analysis = newValue
    },

    SET_SALEDETAILS(state, newValue){
        state.sales_details = newValue
    },

    

 };
  export const actions={

    // add products to shop cart
    addToCart({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post('sales_cart', data).then(() => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add products to shop cart
    addToCart1({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.post('add_held_sale', data).then(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Item added successfully" }
            dispatch('notification/success', message, { root: true })
            dispatch('fetchHeld')
            dispatch('fetchAllHeld')
        }).catch(()=>{
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Failed to add Product" }
            dispatch('notification/error', message, { root: true })
        })
    },

    editCart({ dispatch, commit }, data) {
        commit('SET_SAVE_STATUS', true)
        axios.put(`sales_cart/${data.id}`, data).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
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
        axios.delete(`sales_cart/${data.id}`).then(response => {
            commit('SET_SAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
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

    // Process Sale
    saveSalePrint({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post(`sales`, data).then(response => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            if (response.data.status==200){
                commit('SET_SALE_ID', response.data.id)
            }else if(response.data.status==202) {
                let message = { 'msg': "Failed to record Sale" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to record Sale" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Process Sale
    saveSaleExit({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post(`sales`, data).then(response => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            if (response.status==200){
                let message = { 'msg': "Sale Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==202) {
                let message = { 'msg': "Failed to record Sale" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to record Sale" }
            dispatch('notification/error', message, { root: true })
        })
    },

    holdSale({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post('sale_hold', data).then((response) => {
            commit('SET_SALESAVE_STATUS', false)
            if (response.status==201){
                let message = { 'msg': "No Items in Cart To Hold" }
                dispatch('notification/error', message, { root: true })

            }else{
                dispatch('fetchCart')
                dispatch('fetchTotCart')
                dispatch('fetchHeld')
                let message = { 'msg': "Order Held successfully" }
                dispatch('notification/success', message, { root: true })

            }

        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to Hold a Sale" }
            dispatch('notification/error', message, { root: true })
        })
    },

    unHoldSale({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.put(`sale_hold/${data.id}`, data).then(response => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            dispatch('fetchHeld')
            dispatch('fetchAllHeld')
            if (response.status==200){
                let message = { 'msg': "Order UnHeld successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==201) {
                let message = { 'msg': "First Process the Order in Cart" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to UnHold" }
            dispatch('notification/error', message, { root: true })
        })
    },

    deleteHold({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.delete(`sale_hold/${data.id}`).then(() => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchCart')
            dispatch('fetchTotCart')
            dispatch('fetchHeld')
            let message = { 'msg': "Order Deleted Success" }
            dispatch('notification/success', message, { root: true })
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': 'Failed to delete Order' }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchCart({ commit }) {
        axios.get('sales_cart').then(cart => {
            commit('SET_CART', cart.data)
        })
    },

    fetchItems({ commit }) {
        axios.get('get_items').then(cart => {
            commit('SET_ITEMS', cart.data)
        })
    },

    fetchTotCart({ commit }) {
        axios.get('total_cart').then(total => {
            commit('SET_TOTAL_CART', total.data)
        })
    },
    fetchHeld({ commit }) {
        axios.get('sale_hold').then(total => {
            commit('SET_HOLD', total.data)
        })
    },
    fetchHoldInvoice({ commit },id) {
        axios.get(`sale_hold/${id}`).then(total => {
            commit('SET_HOLD_DETAILS', total.data)
        })
    },
    fetchAllHeld({ commit }) {
        axios.get('all_held_sales').then(total => {
            commit('SET_ALL_HOLD', total.data)
        })
    },

    fetchSales({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('salesanalysis_report', payload).then(total => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALE_ANALYSIS', total.data)
        })
    },
    
    // PrintSaleReceipt({commit},Id){
    //     axios.get(`print_receipt/${Id}`).then(res =>{
    //       commit("SET_SALE_RECEIPT",res)
    //     })   
    // },
    
    returnSale({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post(`return_sales`, data).then(response => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchSales')
            if (response.data.status==200){
                let message = { 'msg': "Sale Return Recorded successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==202) {
                let message = { 'msg': "Failed to record Sale Return" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to record Sale Return" }
            dispatch('notification/error', message, { root: true })
        })
    },
    fetchReturns({ commit }) {
        axios.get('fetch_salereturns').then(total => {
            commit('SET_SALE_RETURNS', total.data)
        })
    },

    fetchPayments({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('payments', payload).then(total => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALE_PAYMENTS', total.data)
        })
    },

    fetchCreditPayments({ commit }, payload) {
        commit('SET_FILTER_STATUS', true)
        axios.post('credit_payments', payload).then(total => {
            commit('SET_FILTER_STATUS', false)
            commit('SET_SALE_CREDITPAYMENTS', total.data)
        })
    },

    fetchPendingOrders({ commit }) {
        axios.get('sales_status').then(order => {
            commit('SET_PENDING_ORDERS', order.data)
        })
    },


    // Confirm Orders
    Confirm_orders({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post('confirmorder', data).then(() => {
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Order successfully confirmed ready" }
            dispatch('notification/success', message, { root: true })
            dispatch('fetchPendingOrders')
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to cofirm order" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchReadyOrders({ commit }) {
        axios.get('readyorders').then(order => {
            commit('SET_READY_ORDERS', order.data)
        })
    },


    // Confirm Served Orders
    Confirm_servedorders({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post('confirmserved', data).then(() => {
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Order successfully confirmed served" }
            dispatch('notification/success', message, { root: true })
            dispatch('fetchReadyOrders')
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to cofirm order" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Fetch for monthly sales analysis report
    fetchMonthlySales({ commit }, payload) {
        axios.post('monthlysales_analysis', payload).then(res => {
            commit('SET_MONTHLYSALE_ANALYSIS', res.data)
        })
    },
    

    // Fetch for monthly profits analysis report
    fetchMonthlyProfits({ commit }, payload) {
        axios.post('monthlyprofits_analysis', payload).then(res => {
            commit('SET_MONTHLYPROFIT_ANALYSIS', res.data)
        })
    },

    fetchSalesDetails({ commit }, payload) {
        axios.post('fetchsales_details', payload).then(res => {
            commit('SET_SALEDETAILS', res.data)
        })
    },

    pickSale({ dispatch, commit }, data) {
        commit('SET_SALESAVE_STATUS', true)
        axios.post(`pick_sales`, data).then(response => {
            commit('SET_SALESAVE_STATUS', false)
            dispatch('fetchSales')
            if (response.data.status==200){
                let message = { 'msg': "Item picked successfully" }
                dispatch('notification/success', message, { root: true })
            }else if(response.status==202) {
                let message = { 'msg': "Failed to record Item picked" }
                dispatch('notification/error', message, { root: true })
            }
        }).catch(()=>{
            commit('SET_SALESAVE_STATUS', false)
            let message = { 'msg': "Failed to record Sale Return" }
            dispatch('notification/error', message, { root: true })
        })
    },
  };