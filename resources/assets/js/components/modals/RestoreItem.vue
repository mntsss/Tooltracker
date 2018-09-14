<template>
  <modal name="restore-item-modal"
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
          Atkurti ištrintą įrankį <a @click="$modal.hide('restore-item-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-form>
      <v-layout>
        <v-flex xs12 class="pa-2" v-if="groups">
          <v-select
            :items="groups"
            :persistent-hint = 'true'
            v-model="groupID"
            menu-props="auto"
            label="Pasirinkite grupę, į kurią atkursite įrankį"
            hide-details
            item-text="ItemGroupName"
            item-value='ItemGroupID'
            prepend-icon="fa-folder"
            outline
            class="mb-4 mt-2"
          ></v-select>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-btn class="ma-2" @click="save()" :disabled="!groupID"><v-icon class="primary--text mr-3">fa-undo</v-icon>Atkurti</v-btn>
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
            groupID: '',
            itemID: null,
            groups: null
        }
    },
    created(){
        this.loadGroups()
    },
  methods: {
    save: function(){
        this.$http.post('/item/restore', {
            'id': this.itemID,
            'groupID': this.groupID
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.loadItem()
                this.$modal.hide('restore-item-modal')
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
    loadGroups: function(){
        this.$http.get('group/list')
        .then(response => {
            this.groups = response.data
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
        this.groupID = null
        this.itemID = null
    }
  }
}
</script>
