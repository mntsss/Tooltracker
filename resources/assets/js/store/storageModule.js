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
      });
    },
    CREATE_STORAGE: function ({commit, dispatch}, {data}){
      return axios.post(endpoints.storageCreate(), data).then( response =>
      {
        dispatch('LOAD_STORAGE_LIST');
      });
    },
    RENAME_STORAGE: function ({commit, dispatch}, {data}){
      return axios.post(endpoints.storageEdit(), data).then( response =>
      {
        dispatch('LOAD_STORAGE_LIST');
      });
    },
    CREATE_GROUP: function ({state, dispatch}, {data}){
      return axios.post(endpoints.groupCreate(), data).then( response =>
      {
        dispatch('LOAD_STORAGE', {id: state.storage.id});
      });
    }
  }
}
export default storageModule;
