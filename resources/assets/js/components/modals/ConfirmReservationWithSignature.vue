<template>
  <modal name="confirm-reservation-with-sign-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Patvirtinti rezervacijos perdavimą <a @click="$modal.hide('confirm-reservation-with-sign-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark">
          <v-layout align-center justify-center>
            <v-flex shrink h5>Laikiama vartotojo {{user}} parašo</v-flex>
          </v-layout>
            <v-layout align-center justify-center>
              <v-flex shrink class="border border-light">
                <VueSignaturePad width="500px" height="500px" ref="signaturePad"/>
              </v-flex>
            </v-layout>
            <v-layout align-center justify-center>
              <v-flex shrink v-if="!loading">
                <v-btn mx-3 @click="clear"><v-icon class="text-danger pr-2">fa-eraser</v-icon>Išvalyti</v-btn><v-btn mx-3 @click = "save"><v-icon class="text-danger pr-2">fa-save</v-icon>Išsaugoti</v-btn>
              </v-flex>
              <v-flex shrink v-else-if="loading">
                <v-progress-circular
                :size="70"
                :width="8"
                color="white"
                indeterminate
                ></v-progress-circular>
              </v-flex>
            </v-layout>
        </div>
    </div>
  </modal>
</template>
<script>

export default{
  data(){
    return {
      id: null,
      user: null,
      sign: null,
      loading: false
    }
  },
  created(){},
  methods: {
    save: function(){
      const { isEmpty, data } = this.$refs.signaturePad.saveSignature()
      if(isEmpty){
        return swal('Klaida!', 'Negalima patvirtinti rezervacijos, kol įrankių gavėjas nepasirašo.', 'error')
      }
      else{
        this.sign = data
        this.loading = true
      }
        this.$http.post('/reservation/confirm/sign', {
          id: this.id,
          sign: this.sign
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('confirm-reservation-with-sign-modal')
                swal(response.data.message, response.data.success, "success")
                this.loading = false
                this.$parent.loadReservations()
            }
        }).catch(error =>{

            if(error.response.status == 422)
            {
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", error.response.data.message, "error");
            }
        })
    },
    clear() {
        this.$refs.signaturePad.clearSignature()
        this.sign = null
    },
    beforeOpen: function(event){
      this.id = event.params.id
      this.user = event.params.user
    },
    beforeClose: function(event){
        this.id = null
        this.user = null
        this.sign = null
        this.loading = false
    }
  }
}
</script>
