<template>
  <Modal modal_name="rename-group-modal">
         <span slot="header">
            Pervadinti grupę
        </span>
        <form slot = "content">
            <div class="form-group">
                <input class="form-control" type="text" name="name" v-model="name" maxlength="40" placeholder="Naujas pavadinimas" autocomplete="off"/>
            </div>
            <v-btn outline color="primary" @click="save()">Išsaugoti</v-btn>
        </form>
  </Modal>
</template>
<script>
import swal from 'sweetalert'
import Modal from './Modal.vue'
export default {
    data(){
        return {
            name: ''
        }
    },
  computed: {
  },
  props: {
    groupID: {
        required: true,
        type: Number
    }
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
    }
},
  components:{
      Modal
  }
}
</script>
