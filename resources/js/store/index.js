import { createStore } from 'vuex';

const store = createStore({
  state: {
    token: null,
    user: {
      name: '',
      regionId: '',
      woreda: '',
      role: '',
    },
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.token = token;
    },
    SET_USER(state, user) {
      state.user.name = user.name;
      state.user.regionId = user.regionId;
      state.user.woreda = user.woreda;
      state.user.role = user.role;
    },
    CLEAR_AUTH(state) {
      state.token = null;
      state.user = {
        name: '',
        regionId: '',
        woreda: '',
        role: '',
      };
    },
  },
  actions: {
    login({ commit }, userData) {
      commit('SET_TOKEN', userData.token);
      commit('SET_USER', userData.user);
    },
    logout({ commit }) {
      commit('CLEAR_AUTH');
    },
  },
  getters: {
    isAuthenticated: state => !!state.token,
    userName: state => state.user.name,
    userRegionId: state => state.user.regionId,
    userWoreda: state => state.user.woreda,
    userRole: state => state.user.role,
  },
});

export default store;
