import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

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
  getUsers: () => `/user/list`,
  filterClosedReservations: (userID = null, startingDate = null, endDate = null) => `closed/user/${userID}/from/${startingDate}/til/${endDate}`,
};
export default axios;
export {endpoints, axios};
