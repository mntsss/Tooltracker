<template>
  <modal name="edit-user-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Redaguoti vartotojo duomenis <a @click="$modal.hide('edit-user-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark">
            <v-form v-model="valid">
                <v-text-field v-model="name" :rules="nameRules" label="Vardas, pavardė" required></v-text-field>
                <v-text-field v-model="phone" :rules="phoneRules" label="Telefonas"></v-text-field>
                <v-btn @click="save()" :disabled="!valid">Išsaugoti</v-btn>
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
            name: '',
            phone: '',
            id: null,
            valid: false,
            nameRules: [
                v => !!v || "Vartotojo vardas būtinas!",
                v => v.length <= 50 || "Vartotojo vardas negali viršyti 50 simbolių!"
            ],
            phoneRules: [ v => v.length <= 14 || "Įvestas per ilgas telefono numeris!"]
        }
    },
  methods: {
    save: function(){

        this.$http.post('/user/edit', {
          username: this.name,
          phone: this.phone,
          id: this.id
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('edit-user-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadUsers();
                this.name = ''
                this.phone = ''
                this.id = null
            }
        }).catch(error =>{

            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
        })
    },
    beforeOpen: function(e){
      var user = e.params.user
      this.name = user.Username
      this.phone = user.UserPhone
      this.id = user.UserID
    }
  }
}
</script>
