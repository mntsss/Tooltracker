<template>
  <modal name="rename-item-modal"
         :height="180"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Pervadinti įrankį
        </div>
        <div class="card-body">
            <form method="post" @submit.prevent="save">
                <div class="form-group">
                    <input class="form-control" type="text" name="name" v-model="name" maxlength="25" placeholder="Naujas pavadinimas" autocomplete="off"/>
                </div>
                <div class="form-group d-flex content-align-center w-100">
                    <button class="btn btn-primary" type="submit">Išsaugoti</button>
                </div>
            </form>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
export default {
    data(){
        return {
            name: '',
            itemID: null
        }
    },
  computed: {
  },
  methods: {
    save: function(){
        this.$http.post('/item/edit/name', {
            'id': this.itemID,
            'name': this.name
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.item.ItemName = this.name;
                this.$modal.hide('rename-item-modal')
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
    }
  }
}
</script>
