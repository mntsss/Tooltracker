<template>
  <div class="loading-parent" style="height: 70vh !important">
      <Loading :active.sync="isLoading"
          :can-cancel="false"
          :is-full-page="fullPage"></Loading>
      <ReturnConfirmModal></ReturnConfirmModal>
      <v-container>
        <v-layout wrap row class="secondary mb-5" justify-center><v-flex shrink headline>Įrankių grąžinimas</v-flex></v-layout>
        <v-layout class="" align-center justify-center>
          <v-flex shrink>
            <v-card tile>
              <div class="text-center p-5">
                <v-icon class="display-4 primary--text" d-flex justify-center>fa-broadcast-tower</v-icon>
              </div>
              <v-progress-linear :indeterminate="true" color="red darken-2" background-color="primary" class="mb-5 mt-5"></v-progress-linear>
              <v-card-title class="headline">
                Skanuokite įrankius, kuriuos grąžinate į sandėlį
              </v-card-title>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
  </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import ReturnConfirmModal from '../modals/ItemReturnConfirmation.vue'
export default{
  data(){
    return {
      isLoading: true,
      fullPage: false,
      item: null
    }
  },
  created(){
    this.isLoading = false
  },
  computed:{
    RfidCode: function(){
      return this.$store.state.recentCode;
    }
  },
  watch:
  {
    RfidCode(val){
      if(this.RfidCode != null && this.item == null)
      {
        this.getItemInfo(this.RfidCode)
        this.$store.commit('resetCode')
      }
    }
  },
  methods: {
      getItemInfo: function(code){
          this.$http.post('/item/findcode', {
              code: code
          }).then(response => {
              if(response.status == 200){
                  if(response.data.status != "withdrew")
                  {
                      if(response.data.status == "suspended")
                        swal('Klaida', 'Įrankis suspenduotas!', 'error')
                      if(response.data.status == "reserved")
                        swal('Klaida', 'Įrankis yra aktyvioje rezervacijoje!', 'error')
                      if(response.data.status == "deleted")
                        swal('Klaida', 'Įrankis ištrintas!', 'error')
                      if(response.data.status == null)
                        swal('Klaida', 'Įrankis sandėlyje!', 'error')
                  }else{
                      this.getWithdrawalInfo(response.data.item.ItemID)
                  }
              }
          }).catch(err => {
              if(err.response.status == 422)
              {
                  swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
              }
              else{
                  swal("Klaida", err.response.data.message, "error");
              }
          })
      },
      getWithdrawalInfo: function(id){
          this.$http.get('/withdrawal/get/'+id).then(response => {
              if(response.status == 200){
                  this.item = response.data
                  this.show('item-return-confirm-modal', {item: this.item})
              }
          }).catch(err => {
              if(err.response.status == 422)
              {
                  swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
              }
              else{
                  swal("Klaida", err.response.data.message, "error");
              }
          })
      },
      show: function(name, params={}){
        this.$modal.show(name, params)
      },
  },
  components: {
    Loading,
    ReturnConfirmModal
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
