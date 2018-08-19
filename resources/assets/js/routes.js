import Main from './components/Main.vue';
import Login from './components/Login.vue';

const routes = [
  {
    path: '/',
    name: 'main',
    component: Main,
    meta: {
      auth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth:false
    }
   },
   {
       path: '/group/:group',
       name: 'group',
       component: require('./components/ItemGroup'),
       props: true,
       meta: {
           auth:true
       }
   },
   {
       path: '/item',
       name: 'item',
       component: require('./components/Item'),
       props: true,
       meta: {
           auth: true
       }
   }
]

export default routes
