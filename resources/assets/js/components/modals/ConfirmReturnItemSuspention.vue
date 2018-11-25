<template>
  <modal name="confirm-return-item-suspention-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :scrollable="true"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header secondary">
            Patvirtinti įrankio sutvarkymą <a @click="$modal.hide('confirm-return-item-suspention-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form v-model="valid">
                <v-layout row mx-0 wrap align-center>
                  <v-flex v-if="!code" class="text-center headline text-danger">Laukiama administratoriaus kortelės...</v-flex>
                  <v-flex v-else-if="code" class="text-center headline text-success">Kortelė nuskaityta!</v-flex>
                </v-layout>
                <v-progress-circular
                  indeterminate
                  color="primary"
                  v-if="pendingRequest"
                ></v-progress-circular>
                <v-btn v-else @click="save()" :disabled="disabled">Patvirtinti</v-btn>
            </v-form>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
export default {
    data(){
        return {
            itemID: null,
            code: null,
            valid: false,
            pendingRequest: false
        }
    },
    computed: {
        visibility: function(){
            return this.$children[0].visibility.modal
        },
        RFIDCode: function(){
            return this.$store.state.recentCode
        },
        disabled: function(){
          if(this.code)
            return false
          return true
        }
    },
    watch: {
        RFIDCode(oldRFIDCode, newRFIDCode){
            if(this.visibility && this.RFIDCode){
                this.code = this.RFIDCode
                this.$store.commit('resetCode')
            }
        }
    },
  methods: {
    save: function(){
        this.pendingRequest = true
        this.$http.post('/item/suspend/return/unconfirmed', {
          id: this.itemID,
          code: this.code,
        }
      ).then((response)=>{
        this.pendingRequest = false
            if(response.status == 200){
                this.$modal.hide('confirm-return-item-suspention-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItem()
            }
        }).catch(error =>{
            this.pendingRequest = false
            if(error.response.status == 422)
            {
              this.code = null
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", error.response.data.message, "error");
            }
        })
    },
    beforeOpen: function(event){
      this.itemID = event.params.itemID
    },
    beforeClose: function(event){
        this.itemID = null
        this.code = null
    }
  }
}
</script>
