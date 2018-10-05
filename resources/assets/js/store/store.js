import Vuex from 'vuex'
import Vue from 'vue'

import ReservationModule from './reservationModule'

Vue.use(Vuex)

const modules = {
    'reservation': ReservationModule
}
const store = new Vuex.Store({
    modules: modules,
    state: {
      recentCode: null,
      user: null,
      routesHistory: [],
      USER_API_CALL_LOADING: false,
      content_loading_screen: true
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
      },
      content_loading_screen_show(state){
          state.content_loading_screen = true
      },
      content_loading_screen_hide(state){
          state.content_loading_screen = false
      },
    },
    actions: {},
    getters: {
      newcode(state){
        state.recentCode
      }
    }
})

export default store;
