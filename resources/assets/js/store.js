import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      recentCode: null
    },
    mutations: {
      newcode(state, code){
        state.recentCode = code;
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
