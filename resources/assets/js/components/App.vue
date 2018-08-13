<style>
  @import '/css/app.css';
</style>
<template>
  <div class="container-fluid remove-all-padding">
    <div v-if="$auth.ready()">
      <TopMeniu v-if="$auth.check()"></TopMeniu>
      <Login v-if="!$auth.check()"></Login>
      <router-view v-if="$auth.check()"></router-view>
    </div>
    <div v-if="!$auth.ready()" class="d-flex justify-content-center align-items-center">
      <LoadingSpinner></LoadingSpinner>
    </div>
  </div>
</template>
<script>
import Login from './Login.vue'
import TopMeniu from './TopMeniu.vue'
import LoadingSpinner from './LoadingSpinner.vue'
export default {

  components: {
    Login,
    TopMeniu,
    LoadingSpinner
  }
}
</script>
