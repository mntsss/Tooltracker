import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
//endpoints
import userEndpoints from './api/user';
import historyEndpoints from './api/history';
import storageEndpoints from './api/storage';
import reservationEndpoints from './api/reservation';
import statistics from './api/statistics';

Vue.use(VueAxios, axios);

//axios setup
const tokenProvider = require('axios-token-interceptor');
axios.defaults.baseURL = window.location.href+'api';
const instance = axios.create({
  baseURL: window.location.href+'api'
});

instance.interceptors.request.use(tokenProvider({
  getToken: () => localStorage.get('access_token')
}));

const endpoints = {
  ...userEndpoints,
  ...historyEndpoints,
  ...storageEndpoints,
  ...reservationEndpoints,
  ...statistics
};

export default axios;
export {endpoints, axios};
