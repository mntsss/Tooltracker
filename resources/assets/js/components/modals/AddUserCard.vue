<template>
  <modal name="add-user-card-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Pridėti vartotojo kortelę <a @click="$modal.hide('add-user-card-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark">
            <v-form v-model="valid">
                <v-layout row wrap align-center>
                  <v-flex v-if="!code" class="border-danger text-center headline text-danger">Laukiama nauja kortelė...</v-flex>
                  <v-flex v-else-if="code" class="border-danger text-center headline text-success">Kortelė nuskaityta!</v-flex>
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
            userID: '',
            code: null,
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

        this.$http.post('/user/addcard', {
          id: this.userID,
          code: this.code,
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('add-user-card-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadUsers();
                this.userID = ''
                this.code = null
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
      this.userID = event.params.id
    }
  }
}
</script>
