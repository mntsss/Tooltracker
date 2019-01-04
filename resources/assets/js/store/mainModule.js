import {endpoints, axios} from "../api";

const mainModule = {
  namespaced: true,
  state:
  {
    items_waiting_confirmation: [],
    tools_suspended_fix: [],
    longest_rented_items: [],
    statistics: {
      monthly_fixes: 0,
      monthly_rent_price: 0,
      items_total: 0,
      items_in_use: 0
    }
  },
  mutations:
  {
    setItemsWaitingConfirmation(state, items){
      state.items_waiting_confirmation = items;
    },
    setToolsSuspendedFix(state, items){
      state.tools_suspended_fix = items;
    },
    setStatisticTiles(state, data){
      state.statistics.items_total = data.items_total;
      state.statistics.items_in_use = data.items_in_use;
      state.statistics.monthly_fixes = data.monthly_fixes;
      state.statistics.monthly_rent_price = data.monthly_rent_price;
    },
    setLongestRentedItems(state, items){
      state.longest_rented_items = items;
    }
  },
  actions:
  {
    LOAD_STATISTICS_TILES: async function({commit}){
      return axios.get(endpoints.statisticsTiles()).then(response => {
        commit('setStatisticTiles', response.data);
      });
    },
    LOAD_ITEMS_FIXING: async function({commit}){
      return axios.get(endpoints.statisticsFixItems()).then(response => {
        commit('setToolsSuspendedFix', response.data);
      });
    },
    LOAD_ITEMS_WAITING_CONFIRMATION: async function({commit}){
      return axios.get(endpoints.statisticsUnconfirmedItemReturns()).then(response => {
        commit('setItemsWaitingConfirmation', response.data);
      });
    },
    LOAD_LONGEST_RENTED_ITEMS: async function({commit}){
      return axios.get(endpoints.statisticsLongestRentedItems()).then(response => {
        commit('setLongestRentedItems', response.data);
      });
    }
  }
};

export default mainModule;
