import {endpoints, axios} from "../api";

const closedReservations = {
  namespaced: true,
  state: {
    reservations: [],
    users: []
  },
  mutations: {
    setUsers(state, users){
      state.users = users
    },
    setReservations(state, reservations){
      state.reservations = reservations;
    }
  },
  actions: {
    LOAD_USERS: async function({commit}){
      return axios.get(endpoints.getUsers()).then((response) => {
        commit('setUsers', response.data);
      })
    },
    LOAD_RESERVATIONS: async function({commit}, {user, from, til}){
      return axios.get(endpoints.filterClosedReservations(user, from, til)).then((response) => {
        commit('setReservations', response.data);
      })
    }
  }
}
export default closedReservations;
