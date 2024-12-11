const state = {
    token: null,
    user: {
        id: null,
        name: null,
        email: null,
        phone_number: null,
        status: null,
        region_id: null,
        woreda_id: null,
        role: null,
    },
    role: null,
};

const mutations = {
    SET_USER(state, payload) {
        state.token = payload.token;
        state.user = {
            id: payload.user.id,
            name: payload.user.name,
            email: payload.user.email,
            phone_number: payload.user.phone_number,
            status: payload.user.status,
            region_id: payload.user.region_id,
            woreda_id: payload.user.woreda_id,
        };
        state.role = payload.role[0];
    },
    LOGOUT_USER(state) {
        state.token = null;
        state.user = {
            id: null,
            name: null,
            email: null,
            phone_number: null,
            status: null,
            region_id: null,
            woreda_id: null,
        };
        state.role = null;
    },
};

const actions = {
    setUser({ commit }, payload) {
        commit("SET_USER", payload);
    },
    logoutUser({ commit }) {
        commit("LOGOUT_USER");
    },
};

const getters = {
    isAuthenticated: (state) => !!state.token,
    authToken: (state) => state.token,
    currentUser: (state) => state.user,
    userRole: (state) => state.role,
    regionId: (state) => state.user.region_id,
    woredaId: (state) => state.user.woreda_id,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
