import axios from "axios";

export const  state = {
    branches:[],
    business_settings: [],
    audits: [],
    save_status:false,
    income_statement:[]
};
export const getters = {
    BusinessSettings: state => state.business_settings,
    OtherBranches: state => state.branches.data,
    Audits: state => state.audits.data,
    SaveStatus: state => state.save_status,
    IncomeStatement: state => state.income_statement,
};
export const mutations= {
    SET_BRANCHES(state,newValue){
        state.branches = newValue
    },
    SET_BUSINESS_SETTINGS(state,newValue){
        state.business_settings = newValue
    },
    SET_AUDITS(state,newValue){
        state.audits = newValue
    },
    SET_SAVE_STATUS(state, newValue) {
        state.save_status = newValue
    },
    SET_INCOME_STATEMENT(state, newValue) {
        state.income_statement = newValue
    },
};
export const actions={
    fetchBranches({ commit }) {
        axios.get('transfer_branches').then(ec_res => {
            commit('SET_BRANCHES', ec_res.data)
        })
    },
    fetchBusinessSettings({ commit }) {
        axios.get('business_settings').then(ec_res => {
            commit('SET_BUSINESS_SETTINGS', ec_res.data)
        })
    },
    fetchAudits({ commit }) {
        axios.get('audits').then(ec_res => {
            commit('SET_AUDITS', ec_res.data)
        })
    },
    fetchIncomeStatement({ commit }, payload) {
        axios.post('income_statement', payload).then(ec_res => {
            commit('SET_INCOME_STATEMENT', ec_res.data)
        })
    },

};

