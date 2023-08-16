import axios from "axios";
export const state = {
    expense_categories: [],
    expenses: [],
    expense_report: [],
    expense_save_status: false,
};
export const getters = {
    ExpenseCategories: state => state.expense_categories.data,
    Expenses: state => state.expenses.data,
    ExpenseSaveStatus: state => state.expense_save_status,
 
};

export const mutations = {
    SET_EXPENSE_CATEGORIES(state, newValue) {
        state.expense_categories = newValue
    },
    SET_EXPENSES(state, newValue) {
        state.expenses = newValue
    },
    SET_EXPENSE_STATUS(state, newValue) {
        state.expense_save_status = newValue
    },
   
};

export const actions = {
    addExpenseCategory({ commit,dispatch },payload) {
        // console.log(payload);
        commit('SET_EXPENSE_STATUS', true);
        axios.post('expense_categories',payload).then(() => {
            dispatch('fetchExpenseCategories');
            let message = { 'msg': "Expense Category added successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error Adding Category" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Edit expense category
    editExpenseCategory({ commit,dispatch },payload) {
        commit('SET_EXPENSE_STATUS', true);
        axios.put(`expense_categories/${payload.id}`, payload).then(() => {
            dispatch('fetchExpenseCategories');
            let message = { 'msg': "Expense Category updated successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error updating Category" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete expense category
    deleteExpenseCategory({ commit,dispatch },payload) {
        // console.log(payload);
        commit('SET_EXPENSE_STATUS', true);
        axios.delete(`expense_categories/${payload.id}`).then(() => {
            dispatch('fetchExpenseCategories');
            let message = { 'msg': "Expense Category deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error deleting Category" }
            dispatch('notification/error', message, { root: true })
        })
    },

    // add expenditure
    recordExpense({ commit,dispatch },payload) {
        // console.log(payload);
        commit('SET_EXPENSE_STATUS', true);
        axios.post('expenses',payload).then(() => {
            dispatch('fetchExpenses');
            let message = { 'msg': "Expense added successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error Adding Expense" }
            dispatch('notification/error', message, { root: true })
        })
    },

      // Edit expense
      editExpenses({ commit,dispatch },payload) {
        commit('SET_EXPENSE_STATUS', true);
        axios.put(`expenses/${payload.id}`, payload).then(() => {
            dispatch('fetchExpenses');
            let message = { 'msg': "Expense updated successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error updating Expense" }
            dispatch('notification/error', message, { root: true })
        })
    },
    // Delete expense 
    deleteExpense({ commit,dispatch },payload) {
        commit('SET_EXPENSE_STATUS', true);
        axios.delete(`expenses/${payload.id}`).then(() => {
            dispatch('fetchExpenses');
            let message = { 'msg': "Expenditure deleted successfully" }
            dispatch('notification/success', message, { root: true })
            commit('SET_EXPENSE_STATUS', false); 
        }).catch(() => {
            commit('SET_EXPENSE_STATUS', false)
            let message = { 'msg': "Error Deleting Expenditure" }
            dispatch('notification/error', message, { root: true })
        })
    },

    fetchExpenseCategories({ commit }) {
        axios.get('expense_categories').then(ec_res => {
            commit('SET_EXPENSE_CATEGORIES', ec_res.data)
        })
    },
    fetchExpenses({ commit }) {
        axios.get('expenses').then(e_res => {
            commit('SET_EXPENSES', e_res.data)
        })
    },
  
};