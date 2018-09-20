import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      recentCode: null,
      user: null,
      routesHistory: [],
      USER_API_CALL_LOADING: false
    },
    mutations: {
      newcode(state, code){
        state.recentCode = code;
      },
      resetCode(state){
        state.recentCode = null
      },
      setUser(state, user){
        state.user = user
      },
      addRouteToHistory(state, route){
        state.routesHistory.unshift(route)
        if(state.routesHistory.length > 10)
          state.routesHistory.splice(-1, 1)
      },
      USER_API_CALL_LOADING(state, val){
          state.USER_API_CALL_LOADING = val
      }
    },
    actions: {},
    getters: {
      newcode(state){
        state.recentCode
      }
    }
})

export default store;
