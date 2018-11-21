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
  filterClosedReservations: (userID = '', from = '', til = '') => {
    if(userID) userID = '/'+userID;
    if(!userID && (from || til)){ userID = "all";}
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `reservation/closed${userID}${from}${til}`},

  filterHistory: (from = '', til = '') => {
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `history/item/all${from}${til}`},
    
  filterUserHistory: (userID = '', from = '', til = '') => {
    if(userID) userID = '/'+userID;
    if(!userID && (from || til)){ userID = "all";}
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `history/user${userID}${from}${til}`;
  }
};
export default axios;
export {endpoints, axios};
