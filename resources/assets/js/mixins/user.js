export default {
    computed: {
        $currentUser() {
            return this.$store.state.user;
        },
    },
    methods: {
      getUser: function(){
        this.$http.get('/user/me').then(response => {
          this.$store.commit("setUser", response.data)
        }).catch(error => {
          console.log(error.response.data.errors)
        })
      }
    },
    watch: {
      '$auth.watch.loaded':{
          handler(ready){
            if(ready)
              this.getUser()
        }
      }
    }
};
