<style>
  @import '/css/app.css';
</style>
<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>

  <div class="container-fluid remove-all-padding">
    <div v-if="$auth.ready()">
      <TopMeniu v-if="$auth.check()"></TopMeniu>
      <Login v-if="!$auth.check()"></Login>
      <router-view v-if="$auth.check()"></router-view>
    </div>
    <div v-if="!$auth.ready()" class="d-flex justify-content-center align-items-center">

    </div>
  </div>
</div>
</template>
<script>
import Login from './Login.vue'
import TopMeniu from './TopMeniu.vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default {
    data(){
        return {
            //isLoading: true,
            fullPage: true
        }
    },
    computed: {
    isLoading: function(){
            return !(this.$auth.ready())
        }
    },
  components: {
    Login,
    TopMeniu,
    Loading
  }
}
</script>
