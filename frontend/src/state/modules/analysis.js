import axios from "axios";
export const state = {
    summaries: [],
    expenses: [],
    expense_report: [],
    filter_status: false,
};
export const getters = {
    Summary: state => state.summaries,
    filterStatus: state => state.filter_status,

};

export const mutations = {
    SET_SUMMARY(state, newValue) {
        state.summaries = newValue
    },
    SET_STATUS(state, newValue) {
        state.filter_status = newValue
    },

};

export const actions = {
    fetchSummary({ commit }, payload) {
        commit('SET_STATUS', true)
        axios.post('dashboard_summary', payload).then(ec_res => {
            commit('SET_STATUS', false)
            commit('SET_SUMMARY', ec_res.data)
        })
    },


};