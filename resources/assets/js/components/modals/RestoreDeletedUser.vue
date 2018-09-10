<template>
  <modal name="restore-user-modal"
         height="auto"
         :scrollable="true"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light headline">
            Atkurti vartotoją <a @click="$modal.hide('restore-user-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark">
            <v-form v-model="valid">
                <v-text-field v-model="password" type="password" :rules="[v => !!v || 'Naujas slaptažodis neįvestas']" label="Naujas slaptažodis" required></v-text-field>
                <v-text-field v-model="repassword" type="password" :rules="[v => v === this.password || 'Slaptažodžiai nesutampa']" label="Pakartokite slaptažodį" required></v-text-field>
                <v-btn @click="save()" :disabled="!valid">Atkurti</v-btn>
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
            password: '',
            repassword: '',
            id: null,
            valid: false,
        }
    },
  methods: {
    save: function(){

        this.$http.post('/user/restore', {
          password: this.password,
          password_confirmation: this.repassword,
          id: this.id
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('restore-user-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadUsers();
                this.password = ''
                this.repassword = ''
                this.id = null
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
      this.id = e.params.id
    }
  }
}
</script>
