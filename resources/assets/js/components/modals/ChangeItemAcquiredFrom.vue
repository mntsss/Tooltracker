<template>
  <modal name="change-item-acquired-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
      <div class="card-header h5 secondary">
          Keisti įrankio įsigijimo vietą <a @click="$modal.hide('change-item-acquired-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-form>
      <v-layout>
        <v-flex xs12>
          <v-text-field v-model="acquired" label="Naujas įsigijimo vieta" type="text" max="150" class="mx-2"></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-btn class="ma-2" @click="save()"><v-icon class="primary--text mr-3">fa-save</v-icon>Išsaugoti</v-btn>
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
            acquired: null,
            itemID: null
        }
    },
  methods: {
    save: function(){
        this.$http.post('/item/edit', {
            'id': this.itemID,
            'acquired': this.acquired
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.itemData.ItemAcquiredFrom = this.acquired;
                this.$modal.hide('change-item-acquired-modal')
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
        this.acquired = event.params.acquired
    },
    beforeClose: function(){
        this.ident = ''
    }
  }
}
</script>
