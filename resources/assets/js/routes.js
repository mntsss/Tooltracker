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
  }
]

export default routes
