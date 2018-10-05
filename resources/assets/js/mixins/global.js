import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default {
    computed: {
        $user() {
            return this.$store.state.user;
        },
        $contentLoading(){
            return this.$store.state.content_loading_screen
        }
    },
    methods: {
      $getUser(){
          if(this.$store)
              if(this.$store.state.user == null && !this.$store.state.USER_API_CALL_LOADING)
              {
                  this.$store.commit("USER_API_CALL_LOADING", true)
                  this.$http.get('/user/me').then(response => {
                      this.$store.commit("setUser", response.data)
                      this.$store.commit("USER_API_CALL_LOADING", false)
                  }).catch(error => {
                      console.log(error.response.data.errors)
                  })
              }
      },
      $contentLoadingHide(){
        this.$store.commit('content_loading_screen_hide')  
      },
      $back(){
          var router = this.$router
          var store = this.$store
          this.$store.state.routesHistory.forEach(function (route, i, array){
              if(route.route == router.currentRoute.name){
                  array.splice(i, 1)
                  return;
              }
              var previousRoute = store.state.routesHistory[0].route
              var previousRouteParams = store.state.routesHistory[0].params
              router.push({name:previousRoute, params:previousRouteParams})
          })
      }
    },
    watch: {
      '$auth.watch.loaded':{
          handler(ready){
            if(ready)
                this.$getUser()
          }
        }
    }
};
