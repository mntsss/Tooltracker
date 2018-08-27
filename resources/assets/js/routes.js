import Main from './components/Main.vue';


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
    path: '/groups',
    name: 'groups',
    component: require('./components/group/Groups'),
    meta: {
      auth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: require('./components/Login'),
    meta: {
      auth:false
    }
   },
   {
       path: '/group/:group',
       name: 'group',
       component: require('./components/group/ItemGroup'),
       props: true,
       meta: {
           auth:true
       }
   },
   {
       path: '/item',
       name: 'item',
       component: require('./components/item/Item'),
       props: true,
       meta: {
           auth: true
       }
   },
   {
       path: '/users',
       name: 'users',
       component: require('./components/user/Users'),
       meta: {
           auth: true
       }
   },
   {
     path: '/users/deleted',
     name: 'deletedUsers',
     component: require('./components/user/DeletedUsers'),
     meta: {
       auth: true
     }
 },
 {
     path: '/objects',
     name: 'objects',
     component: require('./components/object/Objects'),
     meta:{
         auth: true
     }
 }
]

export default routes
