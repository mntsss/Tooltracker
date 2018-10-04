<template>
  <modal name="assign-foreman-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header secondary headline">
            Priskirti darbų vygdytoją <a @click="$modal.hide('assign-foreman-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form v-model="valid">
                <v-select
          :items="users"
          item-text="Username"
          item-value="UserID"
          v-model="UserID"
          hide-details
          label="Darbų vygdytojas"
          prepend-icon="fa-user-tie"
          single-line
        ></v-select>
            <v-layout align-center justify-center pa-3>
                <v-flex shrink>
                    <v-btn @click="save()" :disabled="!valid">Išsaugoti</v-btn>
                </v-flex>
            </v-layout>

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
            ObjectID: null,
            users: null,
            UserID: null,
            valid: false,
        }
    },
  methods: {
    save: function(){

        this.$http.post('/object/foreman/assign', {
          objectID: this.ObjectID,
          userID: this.UserID,
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('assign-foreman-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadObjects();
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
    beforeOpen: function(e){
        this.ObjectID = e.params.objectID
        this.$http.get('user/list').then((response)=>{
            if(response.status == 200){
                this.users = response.data
            }
        }).catch(error => {
            swal('Klaida', error.response.data.message, 'error')
        })
    },
    beforeClose: function(e){
        this.userID = null
        this.objectID = null
    }
  }
}
</script>
