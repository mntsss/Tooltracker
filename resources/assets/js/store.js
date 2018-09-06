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
      userInfo(state, user){
        state.user = user
      },
      addRouteToHistory(state, route){
        state.routeHistory.unshift(route)
        if(state.routeHistory.length > 10)
          state.routeHistory.splice(-1, 1)
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
