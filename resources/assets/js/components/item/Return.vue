<template>
      <v-container>
        <ReturnConfirmModal></ReturnConfirmModal>
        <v-layout wrap row mx-0 class="secondary mb-5" justify-center><v-flex shrink headline>Įrankių grąžinimas</v-flex></v-layout>
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
</template>
<script>
import swal from 'sweetalert'
import ReturnConfirmModal from '../modals/ItemReturnConfirmation.vue'
export default{
  data(){
    return {
      item: null
    }
  },
  mounted(){
    this.$contentLoadingHide()
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
                  if(response.data.state != "Naudojamas"){
                    if(item.state == 'Rezervuotas')
                      return swal("Klaida!", 'Įrankis jau yra pridėtas aktyvioje rezervacijoje...', 'error')
                    else if(item.state == 'Sandėlyje')
                      return swal("Klaida!", 'Įrankis yra sandėlyje (arba negrąžinamas)!', 'error')
                    else if(item.state == 'Ištrintas')
                      return swal("Klaida!", 'Įrankis yra ištrintas!', 'error')
                    else
                      return swal("Klaida!", 'Įrankis yra įšaldytas!', 'error')
                  }
                  else{
                      this.getWithdrawalInfo(response.data.ItemID)
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
      }
  },
  components: {
    ReturnConfirmModal
  }
}
</script>
