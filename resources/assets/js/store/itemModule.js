import {endpoints, axios} from "../api";

const itemModule = {
    namespaced: true,
    state: {
        item: null,
        itemList: []
    },
    mutations: {
        setItem(state, item) {
            state.item = item;
        },
        setItemList(state, items) {
            state.itemList = items;
        }
    },
    actions: {
        LOAD_ITEMS_LIST: function ({commit}, {id}) {
            return axios.get(endpoints.itemList(id)).then(response => {
                commit('setItemList', response.data);
            });
        },
        LOAD_ITEM: function ({commit}, {id}) {
            return axios.get(endpoints.itemGet(id)).then(response => {
                commit('setItem', response.data);
            });
        },
    }
};

export default itemModule;
