<template>
  <modal name="change-item-idnumber-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card bg-dark">
      <div class="card-header h5 bg-dark text-light">
          Keisti įrankio identifikacinį numerį <a @click="$modal.hide('change-item-idnumber-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-form>
      <v-layout>
        <v-flex xs12>
          <v-text-field v-model="ident" label="Naujas identifikacinis numeris" type="text" max="25" class="mx-2"></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-btn class="ma-2" type="submit" @click="save()"><v-icon class="text-danger mr-3">fa-save</v-icon>Išsaugoti</v-btn>
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
            ident: '',
            itemID: null
        }
    },
  methods: {
    save: function(){
        this.$http.post('/item/edit/ident', {
            'id': this.itemID,
            'ident': this.ident
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.itemData.ItemIdNumber = this.ident;
                this.$modal.hide('change-item-idnumber-modal')
                swal(response.data.message, response.data.success, "success")
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
    beforeOpen: function(event){
        this.itemID = event.params.itemID
    },
    beforeClose: function(){
        this.ident = ''
    }
  }
}
</script>
