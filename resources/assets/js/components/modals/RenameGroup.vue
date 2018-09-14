<template>
  <modal name="rename-group-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header secondary">
            Pervadinti grupę
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <input class="form-control" type="text" name="name" v-model="name" maxlength="40" placeholder="Naujas pavadinimas" autocomplete="off"/>
                </div>
                <v-btn outline color="primary" @click="save()">Išsaugoti</v-btn>
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
            groupID: null
        }
    },
  computed: {
  },
  methods: {
    save: function(){
        this.$http.post('/group/rename', {
            'id': this.groupID,
            'name': this.name
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.itemGroup.ItemGroupName = this.name;
                this.$modal.hide('rename-group-modal')
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
        this.groupID = event.params.groupID
    }
  }
}
</script>
