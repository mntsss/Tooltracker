import {endpoints, axios} from "../api";

const storageModule = {
  namespaced: true,
  state: {
    storageList: [],
    storage: null,
  },
  mutations: {
    setStorageList(state, list){
      state.storageList = list;
    },
    setStorage(state, storage){
      state.storage = storage;
    }
  },
  actions: {
    LOAD_STORAGE_LIST: async function({commit}){
      return axios.get(endpoints.storageList()).then(response => {
        commit("setStorageList", response.data);
      });
    },
    LOAD_STORAGE: async function({commit}, {id}){
      return axios.get(endpoints.storageGet(id)).then(response => {
        commit("setStorage", response.data);
      })
    }
  }
}
export default storageModule;
