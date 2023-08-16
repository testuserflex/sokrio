export const state = {
    type: null,
    message: null,
    show: false,
};

export const mutations = {
    success(state, message) {
        state.type = 'success';
        state.message = message;
        state.show = true;
    },
    error(state, message) {
        state.type = 'danger';
        state.message = message;
        state.show = true;
    },
    clear(state) {
        state.type = null;
        state.message = null;
        state.show = false;
    }
};

export const actions = {
    success({ commit,dispatch }, message) {
        commit('success', message);
        setTimeout(() => {
            dispatch('clear');
        }, 4000);
    },
    error({ commit,dispatch }, message) {
        commit('error', message);
        setTimeout(() => {
            dispatch('clear');
        }, 4000);
    },
    clear({ commit }) {
        commit('clear');
    }
};
