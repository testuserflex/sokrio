import axios from "axios";
export const state = {
    units: [],
    categories: [],
    business_products: [],
    services: [],
    branch_products: [],
    products: [],
    view_products: [],
    batches: [],
    save_status: false,
    product_demand: [],
    consumable: [],
    consumable_cart: [],
    sellingprice_changes: [],
    outof_stock: [],
    in_stock: [],
    branchProducts: [],
    filter_status:false

};
export const getters = {
    Units: state => state.units.data,
    SaveStatus: state => state.save_status,
    FilterStatus: state => state.filter_status,
    Categories: state => state.categories.data,
    BProducts: state => state.business_products.data,
    Products: state => state.branch_products.data,
    ViewProducts: state => state.view_products.data,
    ProductsData: state => state.products.data,
    BranchProducts: state => state.branchProducts.data,
    Services: state => state.services.data,
    Batches: state => state.batches,
    ProductDemand: state => state.product_demand,
    Consumables: state => state.consumable.data,
    ConsumablesCart: state => state.consumable_cart.data,
    SellingpriceChanges: state => state.sellingprice_changes.data,
    OutofStock: state => state.outof_stock.data,
    InStock: state => state.outof_stock.data

};

export const mutations = {
    SET_UNITS(state, newValue) {
        state.units = newValue
    },
    SET_CATS(state, newValue) {
        state.categories = newValue
    },
    SET_SERVICES(state, newValue) {
        state.services = newValue
    },
    SET_BUSINESS_PRODUCTS(state, newValue) {
        state.business_products = newValue
    },
    SET_PRODUCTS(state, newValue) {
        state.products = newValue
    },
    SET_BRANCH_PRODUCTS(state, newValue) {
        state.branch_products = newValue
    },
    SET_VIEW_PRODUCTS(state, newValue) {
        state.view_products = newValue
    },
    SET_BATCHES(state, newValue) {
        state.batches = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_PRODUCTDEMAND(state, newValue) {
        state.product_demand = newValue
    },
    SET_CONSUMABLE(state, newValue) {
        state.consumable = newValue
    },
    SET_CONSUMABLE_CART(state, newValue) {
        state.consumable_cart = newValue
    },
    SET_PRICE_CHANGE(state, newValue) {
        state.sellingprice_changes = newValue
    },
    SET_OUTOF_STOCK(state, newValue) {
        state.outof_stock = newValue
    },
    SET_IN_STOCK(state, newValue) {
        state.outof_stock = newValue
    },
    SET_BRANCHPRODUCTS(state, newValue) {
        state.branchProducts = newValue
    },
    SET_FILTER_STATUS(state, newValue) {
        state.filter_status = newValue
    },

};

export const actions = {
    addUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('units', payload).then(() => {
            dispatch('fetchUnits');
            let message = { 'msg': `Unit ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Unit " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Unit
    editUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`units/${payload.id}`, payload).then(() => {
            dispatch('fetchUnits');
            let message = { 'msg': `Unit ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Unit" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Unit
    deleteUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`units/${payload.id}`).then(() => {
            dispatch('fetchUnits');
            let message = { 'msg': `Unit ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    addPCategory({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('product_categories', payload).then(() => {
            dispatch('fetchPCategories');
            let message = { 'msg': `Product Category ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Product Category " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Product Category
    editPCategory({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`product_categories/${payload.id}`, payload).then(() => {
            dispatch('fetchPCategories');
            let message = { 'msg': `Product Category ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Product Category" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Unit
    deletePCategory({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`product_categories/${payload.id}`).then(() => {
            dispatch('fetchPCategories');
            let message = { 'msg': `Product Category ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    addBProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('business_products', payload).then(() => {
            dispatch('fetchBProducts');
            let message = { 'msg': `Business Product ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Business Product " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Business Product
    editBProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`business_products/${payload.id}`, payload).then(() => {
            dispatch('fetchBProducts');
            let message = { 'msg': `Business Product ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Product" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Product
    deleteBProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`business_products/${payload.id}`).then((resp) => {
            dispatch('fetchBProducts');
            if (resp.status == 201) {
                let message = { 'msg': resp.data.message }
                dispatch('notification/error', message, { root: true })
            } else {
                let message = { 'msg': `Business Product ${payload.name} deleted successfully` }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    addProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('products', payload).then((res) => {
            commit('SET_SAVE_STATUS', false);            
            if (res.status == 200) {
                dispatch('fetchProductsData', );
                let message = { 'msg': `Product ${payload.name} added successfully` }
                dispatch('notification/success', message, { root: true })
            } else if (res.status == 202) {
                let message = { 'msg': res.data.message }
                dispatch('notification/error', message, { root: true })
            }            
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Product " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Edit Product
    editProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`products/${payload.id}`, payload).then((res) => {
            commit('SET_SAVE_STATUS', false);
            if (res.status == 200) {
                let message = { 'msg': `Product ${payload.name} updated successfully` }
                dispatch('notification/success', message, { root: true })
            } else if (res.status == 202) {
                let message = { 'msg': res.data.message }
                dispatch('notification/error', message, { root: true })
            }             
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Product" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Product
    deleteProduct({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`products/${payload.id}`).then(() => {
            dispatch('fetchProductsData');
            let message = { 'msg': `Product ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    addProductUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('product_units', payload).then((resp) => {
            dispatch('fetchProducts');
            if (resp.status == 201) {
                let message = resp.data.message;
                alert(message);
                dispatch('notification/error', message, { root: true })
            } else {
                let message = { 'msg': `Product Unit ${payload.unit} added successfully` }
                dispatch('notification/success', message, { root: true })
            }
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Product " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Product
    editProductUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`product_units/${payload.id}`, payload).then(() => {
            dispatch('fetchProducts');
            let message = { 'msg': `Product Unit ${payload.unit} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Product Unit " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Product
    deleteProductUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`product_units/${payload.id}`).then(() => {
            dispatch('fetchProducts');
            let message = { 'msg': `Product Unit ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // change default product unit
    changeDefaultUnit({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('change_base_unit', payload).then((resp) => {
            dispatch('fetchProducts');
            if (resp.status == 200) {
                let message = { 'msg': `Product Unit ${payload.name} set as default for product ${payload.product}` }
                dispatch('notification/success', message, { root: true })
                commit('SET_SAVE_STATUS', false);
            } else if (resp.status == 201) {
                let message = { 'msg': "Error! " + resp.message }
                dispatch('notification/error', message, { root: true })
                commit('SET_SAVE_STATUS', false);
            }
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error changing default Product Unit" }
            dispatch('notification/error', message, { root: true })
        })
    },

    addService({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post('services', payload).then(() => {
            dispatch('fetchServices');
            let message = { 'msg': `Service ${payload.name} added successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Adding Service " + error }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit Service
    editService({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`services/${payload.id}`, payload).then(() => {
            dispatch('fetchServices');
            let message = { 'msg': `Service ${payload.name} updated successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error updating Service" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete Service
    deleteService({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.delete(`services/${payload.id}`).then(() => {
            dispatch('fetchServices');
            let message = { 'msg': `Service ${payload.name} deleted successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch((error) => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error " + error }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Stock roconciliation
    addQty({ commit,dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.put(`reconcile/${payload.id}`, payload).then(() => {
            commit('SET_SAVE_STATUS', false);
            dispatch('viewProducts', payload);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
        })
    },


    SaveReconcile({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post(`save_reconcile`, { type: 0 }).then(() => {
            dispatch('viewProducts', payload);
            let message = { 'msg': `Stock Reconciled successfully` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error Reconciling Stock" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchUnits({ commit }) {
        axios.get('units').then(ec_res => {
            commit('SET_UNITS', ec_res.data)
        })
    },
    fetchPCategories({ commit }) {
        axios.get(`product_categories`).then(ec_res => {
            commit('SET_CATS', ec_res.data)
        })
    },
    fetchBProducts({ commit }) {
        axios.get(`business_products`).then(ec_res => {
            commit('SET_BUSINESS_PRODUCTS', ec_res.data)
        })
    },
    fetchProducts({ commit }) {
        axios.get(`products`).then(ec_res => {
            commit('SET_BRANCH_PRODUCTS', ec_res.data)
        })
    },
    fetchProductsData({ commit }, payload) {
        axios.post('products_data', payload).then(ec_res => {
            commit('SET_PRODUCTS', ec_res.data)
        })
    },
    viewProducts({ commit }, payload) {
        commit('SET_FILTER_STATUS', true);
        axios.post(`view_products`, payload).then(ec_res => {
            commit('SET_VIEW_PRODUCTS', ec_res.data)
            commit('SET_FILTER_STATUS', false);
        })
    },
    fetchBranchProducts({ commit }, payload) {
        axios.post(`branch_products`, payload).then(ec_res => {
            commit('SET_BRANCHPRODUCTS', ec_res.data)
        })
    },
    fetchServices({ commit }) {
        axios.get(`services`).then(ec_res => {
            commit('SET_SERVICES', ec_res.data)
        })
    },
    fetchBatches({ commit }) {
        axios.get(`batches`).then(ec_res => {
            commit('SET_BATCHES', ec_res.data)
        })
    },
    fetchProductdemand({ commit }, payload) {
        axios.post(`product_demand`, payload).then(ec_res => {
            commit('SET_PRODUCTDEMAND', ec_res.data)
        })
    },

    // Fetch Selling price changes
    fetchPriceChanges({ commit }) {
        axios.get(`price_changes`).then(ec_res => {
            commit('SET_PRICE_CHANGE', ec_res.data)
        })
    },

    TransferProducts({ commit, dispatch }, payload) {
        commit('SET_SAVE_STATUS', true);
        axios.post(`transfer_products`, payload).then(() => {
            dispatch('fetchProducts');
            let message = { 'msg': `Product transfer successfully done` }
            dispatch('notification/success', message, { root: true })
            commit('SET_SAVE_STATUS', false);
        }).catch(() => {
            commit('SET_SAVE_STATUS', false)
            let message = { 'msg': "Error transfering products" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // Fetch out of stock products
    fetchOutofStock({ commit }) {
        axios.get(`outofstock`).then(ec_res => {
            commit('SET_OUTOF_STOCK', ec_res.data)
        })
    },

    // Fetch in stock products
    fetchInStock({ commit }) {
        axios.get(`instock`).then(ec_res => {
            commit('SET_IN_STOCK', ec_res.data)
        })
    },

};