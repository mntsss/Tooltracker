<template>
  <modal name="item-warranty-fix-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
      <div class="card-header h5 secondary">
          Įšaldyti įrankį garantiniam taisymui <a @click="$modal.hide('item-warranty-fix-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-form>
      <v-layout>
        <v-flex xs12>
          <v-textarea v-model="note" label="Komentaras" type="text" max="500" class="mx-2"></v-textarea>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-progress-circular
            indeterminate
            color="primary"
            v-if="pendingRequest"
          ></v-progress-circular>
          <v-btn v-else class="ma-2" @click="save()"><v-icon class="primary--text mr-3">fa-wrench</v-icon>Įšaldyti taisymui</v-btn>
        </v-flex>
      </v-layout>
      </v-form>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
export default {
    data(){
        return {
            note: '',
            itemID: null,
            pendingRequest: false
        }
    },
  methods: {
    save: function(){
      this.pendingRequest = true
        this.$http.post('/item/suspend/warrantedfix', {
            'id': this.itemID,
            'note': this.note
        }).then((response)=>{
          this.pendingRequest = false
            if(response.status == 200){
                this.$parent.itemStatus = "Garantinis taisymas"
                this.$modal.hide('item-warranty-fix-modal')
                swal(response.data.message, response.data.success, "success")
            }
        }).catch(error =>{
          this.pendingRequest = false
            if(error.response.status == 422)
            {
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
    beforeClose: function(){
        this.note = ''
    }
  }
}
</script>
