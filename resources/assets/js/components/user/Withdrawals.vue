<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>

  <div class="container" style="min-height: 70vh !important">
    <div class="card" v-if="user">
      <v-layout row wrap align-center class="card-header pb-0 pt-0 mx-0 secondary v-toolbar" >
          <v-flex headline shrink justify-start align-content-center>
              <a @click="back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline" v-if="user">Vartotojo {{user.Username}} įrankiai</div>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="user.withdrawals.length > 0">
        <router-link tag="div" class="row remove-side-margin cursor-pointer" :to="{ name: 'item', params: { itemProp: {item: withdrawal.item, state: null}}}" v-for="(withdrawal, index) in user.withdrawals" :key="index">
          <div class="col-6">
            {{withdrawal.item.ItemName}}
          </div>
          <div class="col text-center">
            {{withdrawal.created_at}}
          </div>
      </router-link>
      </div>
      <div class="card-body mt-1 border border-dark" v-else-if="user.withdrawals.length == 0">
        <div class="text-center h5 pa-5">
          Vartotojas įrankių neturi...
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'

  export default {
    data(){
      return {
        user: null,
        isLoading: true,
        fullPage: false,
      }
    },
    props: {
        userID: {
            required: true,
            type: Number
        }
    },
    created(){
        this.loadUser()
    },
    methods: {
        loadUser: function(){
            return this.$http.get('/user/withdrawals/'+this.userID).then((response)=>{
                if(response.status == 200){
                    this.user = response.data
                    this.isLoading = false
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        },
      back: function(){
          var previousRoute = this.$store.state.routesHistory[0].route
          var previousRouteParams = this.$store.state.routesHistory[0].params
          this.$router.push({name:previousRoute, params:previousRouteParams})
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
