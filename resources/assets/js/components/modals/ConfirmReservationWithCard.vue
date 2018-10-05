<template>
  <modal name="confirm-reservation-with-card-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         :scrollable="true"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header secondary">
            Patvirtinti rezervacijos perdavimą <a @click="$modal.hide('confirm-reservation-with-card-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form v-model="valid">
                <v-layout row mx-0 wrap align-center>
                  <v-flex v-if="!code" class="border-danger text-center headline text-danger">Laukiama vartotojo <span>{{user}}</span>  kortelė...</v-flex>
                  <v-flex v-else-if="code" class="text-center headline text-success">Kortelė nuskaityta!</v-flex>
                </v-layout>

                <v-btn @click="save()" :disabled="disabled">Išsaugoti</v-btn>
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
            id: null,
            code: null,
            user: '',
            valid: false,
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

        this.$http.post('/reservation/confirm/card', {
          id: this.id,
          code: this.code,
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('confirm-reservation-with-card-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadReservations()
            }
        }).catch(error =>{

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
      this.id = event.params.id
      this.user = event.params.user
    },
    beforeClose: function(event){
        this.id = null
        this.code = null
        this.user = null
    }
  }
}
</script>
