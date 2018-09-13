<template>
  <modal name="assign-rented-item-modal"
         height="auto"
         :scrollable="true"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose"
         >
    <div class="card">
        <div class="card-header bg-dark text-light">
            Priskirti įrankį objektui <a @click="$modal.hide('assignr-rented-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark">
            <v-form v-model="valid">
                <v-select v-model="object" :items = "objects" item-text="ObjectName" item-value="ObjectID" label="Objektas" required></v-select>
                <v-btn @click="save()" :disabled="!object">Priskirti</v-btn>
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
            object: null,
            itemID: null,
            valid: false,
            objects: []
        }
    },
    created(){
      this.loadObjects()
    },
  methods: {
    save: function(){

        this.$http.post('/rented/assign', {
          id: this.itemID,
          object: this.object
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('assign-rented-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItem();
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
    loadObjects: function(){
      this.$http.get('object/list').then((response)=>{
          if(response.status == 200){
              this.objects = response.data
          }
      }).catch(error => {
          swal('Klaida', error.response.data.message, 'error')
      })
    },
    beforeOpen(event){
      this.itemID = event.params.itemID
    },
    beforeClose(){
      this.itemID = null
      this.object = null
    }
  }
}
</script>
