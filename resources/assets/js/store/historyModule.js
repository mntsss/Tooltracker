import {endpoints, axios} from "../api";

const historyModule = {
  namespaced: true,
  state:
  {
    actions: [],
    users: []
  },
  mutations:
  {
    setActions(state, actions){
      state.actions = actions;
    },
    setUsers(state, users){
      state.users = users;
    }
  },
  actions:
  {
    LOAD_USERS: async function({commit}){
      return axios.get(endpoints.getUsers()).then((response) => {
        commit('setUsers', response.data);
      })
    },
    LOAD_ACTIONS: async function({commit}, {from, til}){
      return axios.get(endpoints.filterHistory(from, til)).then((response) => {
        commit('setActions', response.data)
      })
    },
    LOAD_USER_ACTIONS: async function({commit}, {user, from, til}){
      return axios.get(endpoints.filterUserHistory(user, from, til)).then((response) => {
        commit('setActions', response.data)
      })
    }
  }
};

export default historyModule;
