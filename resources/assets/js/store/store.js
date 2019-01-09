import Vuex from 'vuex';
import Vue from 'vue';

import ReservationModule from './reservationModule';
import ClosedReservationsModule from './closedReservationsModule';
import HistoryModule from './historyModule';
import StorageModule from './storageModule';
import MainModule from './mainModule';
import ItemModule from './itemModule';

Vue.use(Vuex);

const modules = {
    'reservation': ReservationModule,
    'closedReservations': ClosedReservationsModule,
    'history': HistoryModule,
    'storage': StorageModule,
    'main': MainModule,
    'item': ItemModule,
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
