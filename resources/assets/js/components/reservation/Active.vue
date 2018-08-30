<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>
    <v-container>
      <v-layout row wrap mx-0 align-center justify-center class="theme--dark v-toolbar">
        <v-flex shrink headline>Aktyvios rezervacijos</v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default{
  data(){
    return {
      isLoading: true,
      fullPage: false
    }
  },
  created(){
    this.loadReservations();
  },
  methods:{
    loadReservations: function(){
      this.$http.get('/reservation/list').then((response)=> {
        if(response.status == 200){
          this.isLoading = false
          console.log(response.data)
        }
      }).catch(error => {
        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
      })
    }
  },
  components: {
    Loading
  }
}
</script>
<style>
  .loading-parent{
    position: relative;
  }
</style>
