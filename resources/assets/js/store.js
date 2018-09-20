import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      recentCode: null,
      user: null,
      routesHistory: []
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
