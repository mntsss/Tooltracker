require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSignaturePad from 'vue-signature-pad';
import vmodal from 'vue-js-modal'
import swal from 'sweetalert'
import 'beautify-scrollbar/dist/index.css';
import api from './api'

import Vuetify from 'vuetify'

import routes from './routes';
import store from './store/store';

import App from './components/App.vue'

import GlobalMixin from './mixins/global';

Vue.use(VueRouter);
Vue.use(vmodal);
Vue.use(VueSignaturePad);
Vue.use(Vuetify, {
  theme: {
    primary: '#00768E',
    secondary: '#c4c4c4',
    accent: '#8c9eff',
    error: '#DC3545'
  }});

// router init
const router = new VueRouter({
  mode: 'history',
  routes
});

router.beforeEach((to, from, next) => {
  var currentRoute = from.name
  var currentParams = from.params
  store.commit('content_loading_screen_show')
  if(from.name != null){
      if(store.state.routesHistory.length > 0){
          if(store.state.routesHistory[0].route == to.name){
              next()
          }
          else {
              store.commit('addRouteToHistory', {'route': currentRoute, 'params': currentParams})
          }
      }
      else{
          store.commit('addRouteToHistory', {'route': currentRoute, 'params': currentParams})
      }
  }
  next()
})
Vue.router = router;
Vue.store = store;
//jwt auth init
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
   tokenDefaultName: 'access_token',
});

//Define global variable
Vue.prototype.$uploadPath = "/media/items/"
// process.env.MIX_IMAGE_UPLOAD_ROUTE
Vue.mixin(GlobalMixin);
//main app init
App.router = Vue.router;
App.store = Vue.store;
new Vue(App).$mount('#app');
