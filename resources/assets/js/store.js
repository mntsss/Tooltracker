import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
      recentCode: null,
      user: null
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
