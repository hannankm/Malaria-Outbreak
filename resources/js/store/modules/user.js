import Cookies from "js-cookie"; // Import js-cookie

const state = {
    token: Cookies.get("authToken") || null, // Retrieve token from cookies
    user: Cookies.get("user")
        ? JSON.parse(Cookies.get("user"))
        : {
              id: null,
              name: null,
              email: null,
              phone_number: null,
              status: null,
              region_id: null,
              woreda_id: null,
          },
    role: Cookies.get("userRole") || null, // Retrieve role from cookies
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
        state.role = payload.role;

        // Set cookies (with optional expiry, e.g., 7 days)
        Cookies.set("authToken", payload.token, { expires: 7 });
        Cookies.set("user", JSON.stringify(payload.user), { expires: 7 });
        Cookies.set("userRole", payload.role, { expires: 7 });
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

        // Remove cookies
        Cookies.remove("authToken");
        Cookies.remove("user");
        Cookies.remove("userRole");
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
    authToken: (state) => state.authToken,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
