<template>
  <modal name="item-return-confirm-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
         <ItemSuspend></ItemSuspend>
    <div v-if="item" class="card d-flex bg-dark pt-0 mt-0 px-0">
      <div class="overlay position-absolute h-100 w-100 bg-dark" v-if="waitingDialog">
        <div class="headline text-light">
               <a @click="$modal.hide('item-return-confirm-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
          </div>
        <v-container>
          <v-layout align-center justify-center row fill-height mt-5>
            <v-flex shrink mt-5>
              <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
            </v-flex>
          </v-layout>
          <v-layout align-center justify-center v-if="!loading">
            <v-flex shrink align-center class="h4 text-danger text-center mt-3">
              Laukiama administratoriaus identifikacinės kortelės...
            </v-flex>
          </v-layout>
          <v-layout align-center justify-center v-else-if="loading">
            <v-flex shrink align-center class="h4 text-light text-center mt-3">
              Prašome palaukti...
            </v-flex>
          </v-layout>
        </v-container>
      </div>
      <div class="card-header headline bg-dark text-light">
          Grąžinamo įrankio patvirtinimas <a @click="$modal.hide('item-return-confirm-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-layout align-center justify-center v-if="item.last_withdrawal.image" class="ma-5">
        <v-flex shrink>
          <vue-images :imgs="images"
                            :modalclose="true"
                            :keyinput="true"
                            :mousescroll="true"
                            :showclosebutton="true"
                            :showcaption="true"
                            :showimagecount="false"
                            :showthumbnails="true">
          </vue-images>
        </v-flex>
      </v-layout>
      <v-layout v-if="item.ItemConsumable">
        <v-text-field
          v-model="quantity"
          name="quantity"
          placeholder="Grąžinamas kiekis"
          type ="number"
          min = "0"
          max = "9999"></v-text-field>
      </v-layout>
      <v-layout align-center>
        <v-flex shrink>
          <v-btn @click="suspend()" class="ma-3"><v-icon class="text-danger mr-2">fa-lock</v-icon>Įšaldyti (įrankis sugadintas)</v-btn>
          <v-btn @click="confirm()" class="ma-3"><v-icon class="text-danger mr-2">fa-check</v-icon>Patvirtinti grąžinimą</v-btn>
        </v-flex>
      </v-layout>
    </div>
  </modal>
</template>
<script>
import vueImages from 'vue-images'
import swal from 'sweetalert'
import ItemSuspend from './ItemSuspendUnconfirmedReturn.vue'
export default{
  data(){
    return {
      item: null,
      quantity: null,
      waitingDialog: false,
      code: null,
      loading: false
    }
  },
  computed: {
    images: function(){
      if(this.item != null){
        return [{imageUrl: this.$uploadPath+this.item.last_withdrawal.image.ImageName, caption: this.item.last_withdrawal.image.created_at}]
      }
    },
    visibility: function(){
        return this.$children[0].visibility.modal
    },
    RFIDCode: function(){
        return this.$store.state.recentCode
    }
},
watch: {
    RFIDCode(oldRFIDCode, newRFIDCode){
        if(this.visibility && this.RFIDCode && this.waitingDialog){
            this.code = this.RFIDCode;
            this.$store.commit('resetCode')
            this.save()
        }
    }
},
  methods: {
    beforeOpen(event){
      this.item = event.params.item
      this.quantity = this.item.last_withdrawal.ItemWithdrawalQuantity
    },
    beforeClose(){
      this.item = null
      this.code = null
      this.waitingDialog = false
      this.loading = false
      this.$parent.item = null
    },
    confirm: function(){
      this.waitingDialog = true
    },
    save: function(){
      this.loading = true
      this.$http.post('/item/return/card', {
        code: this.code,
        id: this.item.last_withdrawal.ItemWithdrawalID,
        quantity: this.quantity
      }).then(response => {
        if(response.status == 200){
          swal(response.data.message, response.data.success, 'success').then(value => { this.$modal.hide('item-return-confirm-modal')})
        }
      }).catch(error =>{

          if(error.response.status == 422)
          {
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error").then(value => { this.$modal.hide('item-return-confirm-modal')})
          }
          else{
              swal("Klaida", error.response.data.message, "error").then(value => { this.$modal.hide('item-return-confirm-modal')})
          }
      })
    },
    suspend: function(){
      this.$modal.show('item-suspend-unconfirmed-modal', {item: this.item})

    }
  },
  components:{
    vueImages,
    ItemSuspend
  }
}
</script>
<style>
.overlay{
  z-index: 5;
  background-color: #303030 !important;
}

</style>
