require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuex from 'vuex'
import VueSignaturePad from 'vue-signature-pad';
import vmodal from 'vue-js-modal'
import swal from 'sweetalert'

import Vuetify from 'vuetify'

import routes from './routes';
import store from './store';

import App from './components/App.vue'

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(vmodal);
Vue.use(Vuex);
Vue.use(VueSignaturePad);
Vue.use(Vuetify, {
  theme: {
    primary: '#00768E',
    secondary: '#333333',
    accent: '#8c9eff',
    error: '#DC3545'
  }});
//api requests setup with jwt tokens
const tokenProvider = require('axios-token-interceptor');
axios.defaults.baseURL = window.location.href+'api';
const instance = axios.create({
  baseURL: window.location.href+'api'
});

instance.interceptors.request.use(tokenProvider({
  getToken: () => localStorage.get('access_token')
}));


// router init
const router = new VueRouter({
  mode: 'history',
  routes
});

Vue.router = router;
Vue.store = store;
//jwt auth init
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
   tokenDefaultName: 'access_token',
});

//Subscribing to pusher channel
Echo.channel('code-channel')
  .listen('ReceivedCode', (e) => {
    store.commit('newcode', e.code)
  });

//main app init
App.router = Vue.router;
App.store = Vue.store;
new Vue(App).$mount('#app');
