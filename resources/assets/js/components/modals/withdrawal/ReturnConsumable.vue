<template>
  <modal name="item-return-consumable-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div v-if="withdrawal" class="card d-flex pt-0 mt-0 px-0">
      <div class="card-header secondary headline">
          Suvartojamų įrankių grąžinimas iš objekto <a @click="$modal.hide('item-return-consumable-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-layout>
        <v-text-field
          class="mx-3"
          v-model="quantity"
          name="quantity"
          label="Grąžinamas kiekis"
          type ="number"
          min = "0"
          max = "9999"></v-text-field>
      </v-layout>
      <v-layout align-center>
        <v-flex shrink>
          <v-btn @click="save()" class="ma-3"><v-icon class="primary--text mr-2">fa-check</v-icon>Patvirtinti grąžinimą</v-btn>
        </v-flex>
      </v-layout>
    </div>
  </modal>
</template>
<script>
import vueImages from 'vue-images'
import swal from 'sweetalert'
export default{
  data(){
    return {
      withdrawal: null,
      objectID: null,
      quantity: null
    }
  },

  methods: {
    beforeOpen(event){
      this.withdrawal = event.params.withdrawal
      this.quantity = this.withdrawal.ItemWithdrawalQuantity
      this.objectID = event.params.objectID
    },
    beforeClose(){
      this.withdrawal = null
      this.objectID = null
    },
    save: function(){
      if(this.quantity > this.withdrawal.ItemWithdrawalQuantity){
        swal("Klaida", "Grąžinamas kiekis negali viršyti objekte naudojamo kiekio!", "error");
        return;
      }
      this.$http.post('/item/return/consumable', {
        objectID: this.objectID,
        id: this.withdrawal.item.ItemID,
        quantity: this.quantity
      }).then(response => {
        if(response.status == 200){
          swal(response.data.message, response.data.success, 'success').then(value => { this.$modal.hide('item-return-consumable-modal')})
          this.parentReload()
        }
      }).catch(error =>{

          if(error.response.status == 422)
          {
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error").then(value => { this.$modal.hide('item-return-consumable-modal')})
          }
          else{
              swal("Klaida", error.response.data.message, "error").then(value => { this.$modal.hide('item-return-consumable-modal')})
          }
      })
    },
    parentReload: function(){
        this.$emit('reload')
    }
  }
}
</script>
