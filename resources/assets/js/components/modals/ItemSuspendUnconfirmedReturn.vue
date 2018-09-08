<template>
  <modal name="item-suspend-unconfirmed-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card bg-dark" v-if="item">
      <div class="card-header h5 bg-dark text-light">
          Įrankio {{item.ItemName}} įšaldymas dėl apgadinimo <a @click="$modal.hide('item-suspend-unconfirmed-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-layout>
        <v-flex xs12>
          <v-textarea v-modal="note" box label="Komentaras" type="text" max="500" counter="500" class="mx-2"></v-textarea>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-btn class="ma-5" @click="save()"><v-icon class="text-danger mr-3">fa-lock</v-icon>Įšaldyti</v-btn>
        </v-flex>
      </v-layout>
    </div>
  </modal>
</template>
<script>

export default{
  data(){
    return {
      item: null,
      note: null
    }
  },
  methods: {
    beforeOpen(event){
      this.item = event.params.item
    },
    beforeClose(){
      this.item = null
      this.note = null
    },
    save: function(){
      this.$http.post('/item/suspend/unconfirmedreturn', {id: this.item.ItemID, note: this.note})
      .then(response => {
        if(response.status == 200){
          swal(response.data.message, response.data.success, 'success').then(val => {this.$modal.hide('item-return-confirm-modal')})
          this.$emit('reload')
        }
      }).catch(error =>{

          if(error.response.status == 422)
          {
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error")
          }
          else{
              swal("Klaida", error.response.data.message, "error").then(value => { this.$modal.hide('item-return-confirm-modal')})
          }
      })
    }
  }
}
</script>
