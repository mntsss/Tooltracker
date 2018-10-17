const routes = [
  {
    path: '/',
    name: 'main',
    component: require('./components/main/Main'),
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
     path: '/item/deleted',
     name: 'deletedItems',
     component: require('./components/group/Deleted'),
     meta: {
         auth: true
     }
   },
   {
     path: '/item/suspended',
     name: 'suspendedItems',
     component: require('./components/group/Suspended'),
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
 },
 {
     path: '/objects/closed',
     name: 'closedObjects',
     component: require('./components/object/Closed'),
     meta:{
         auth: true
     }
 },
 {
    path: '/reservation/cart',
    name: 'cart',
    component: require('./components/reservation/Cart'),
    meta: {
      auth: true
    }
 },
 {
   path: '/reservation/active',
   name: 'reservations',
   component: require('./components/reservation/Active'),
   meta: {
     auth: true
   }
 },
 {
   path: '/reservation/closed',
   name: 'closedReservations',
   component: require('./components/reservation/Closed'),
   meta: {
     auth: true
   }
 },
 {
   path: '/reservation/assing',
   name: 'assign',
   component: require('./components/reservation/Assign'),
   meta: {
     auth: true
   }
 },
 {
   path: '/item/return',
   name: 'return',
   component: require('./components/item/Return'),
   meta: {
     auth: true
  }
},
{
   path: '/rented',
   name: 'rentedItems',
   component: require('./components/group/Rented'),
   meta: {
     auth: true
   }
},
{
   path: '/rented/item',
   name: 'rentedItem',
   component: require('./components/item/Rented'),
   props: true,
   meta: {
      auth: true
   }
},
{
   path: '/users/withdrawals',
   name: 'userWithdrawals',
   component: require('./components/user/Withdrawals'),
   props: true,
   meta: {
      auth: true
   }
},
{
  path: '/history',
  name: 'history',
  component: require('./components/history/Main'),
  meta:{
    auth: true
  }
},
{
    path: '/history/item',
    name: 'itemHistory',
    component: require('./components/history/Item'),
    props: true,
    meta: {
        auth: true
    }
},
{
    path: '/history/user',
    name: 'userHistory',
    component: require('./components/history/User'),
    props: true,
    meta: {
        auth: true
    }
},
]

export default routes
