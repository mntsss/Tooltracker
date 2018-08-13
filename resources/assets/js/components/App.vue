<style>
  @import '/css/app.css';
</style>
<template>
  <div class="container-fluid remove-all-padding">
    <TopMeniu v-if="$auth.check()"></TopMeniu>
      <Login v-if="!$auth.check()"></Login>
      <router-view v-if="$auth.check()"></router-view>
  </div>
</template>
<script>
import Login from './Login.vue'
import TopMeniu from './TopMeniu.vue'
export default {
  mounted: function(){
    console.log(this.$auth.check());
  },
  components: {
    Login,
    TopMeniu,
  }
}
</script>
