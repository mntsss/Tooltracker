<template>
  <modal name="create-user-modal"
         height="auto"
         :scrollable="true"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3">
    <div class="card">
        <div class="card-header secondary">
            Pridėti vartotoją <a @click="$modal.hide('create-user-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form v-model="valid">
                <v-text-field v-model="email" :rules="emailRules" label="El paštas" required></v-text-field>
                <v-text-field v-model="name" :rules="nameRules" label="Vardas, pavardė" required></v-text-field>
                <v-select v-model="role" :items = "userRoles" :rules="roleRules" label="Vartotojo tipas" required></v-select>
                <v-text-field v-model="password" :rules="passwordRules" type="password" label="Slaptažodis" required></v-text-field>
                <v-text-field v-model="repassword" :rules="repasswordRules" type="password" label="Pakartokite slaptažodį" required></v-text-field>
                <v-text-field v-model="phone" :rules="phoneRules" label="Telefonas"></v-text-field>
                <v-layout row mx-0 wrap align-center v-if="!nocode">
                  <v-flex v-if="!code" class="border-danger text-center headline text-danger">Laukiama nauja kortelė...</v-flex>
                  <v-flex v-else-if="code" class="border-danger text-center headline text-success">Kortelė nuskaityta!</v-flex>
                </v-layout>
                <v-checkbox v-model="nocode" label="Be identifikacinės kortelės"></v-checkbox>
                <v-progress-circular
                  indeterminate
                  color="primary"
                  v-if="pendingRequest"
                ></v-progress-circular>
                <v-btn v-else @click="save()" :disabled="disabled">Išsaugoti</v-btn>
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
            pendingRequest: false,
            email: '',
            name: '',
            role: '',
            phone: '',
            password: '',
            repassword: '',
            code: null,
            nocode: false,
            valid: false,
            emailRules: [
                v => !!v || "El. paštas būtinas!",
                v => v.length <= 50 || "El. pašto adresas per ilgas!"
            ],
            nameRules: [
                v => !!v || "Vartotojo vardas būtinas!",
                v => v.length <= 50 || "Vartotojo vardas negali viršyti 50 simbolių!"
            ],
            passwordRules: [
              v => !!v || "Slaptažodis būtinas!",
              v => v.length >= 6 || "Slaptažodis negali būti trumpesnis nei 6 simboliai."
            ],
            repasswordRules: [
              v => v === this.password || "Slaptažodžiai nesutampa!"
            ],
            phoneRules: [ v => v.length <= 14 || "Įvestas per ilgas telefono numeris!"],
            roleRules: [ v => !!v || "Nenurodytas vartotojo tipas!"],
            userRoles: [ 'Darbuotojas', 'Administratorius']
        }
    },
    computed: {
        visibility: function(){
            return this.$children[0].visibility.modal
        },
        RFIDCode: function(){
            return this.$store.state.recentCode
        },
        disabled: function(){
          if(this.code || this.nocode)
          {
            if(this.valid)
              return false
          }
          return true
        }
    },
    watch: {
        RFIDCode(oldRFIDCode, newRFIDCode){
            if(this.visibility && this.RFIDCode){
                this.code = this.RFIDCode
                this.$store.commit('resetCode')
            }
        }
    },
  methods: {
    save: function(){
        this.pendingRequest = true
        this.$http.post('/user/create', {
          email: this.email,
          username: this.name,
          password: this.password,
          password_confirmation: this.repassword,
          phone: this.phone,
          role: this.role,
          code: this.code,
          nocode: this.nocode
        }
      ).then((response)=>{
          this.pendingRequest = false
            if(response.status == 200){
                this.$modal.hide('create-user-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadUsers();
                this.email = ''
                this.name = ''
                this.code = null
                this.password = ''
                this.repassword = ''
                this.phone = ''
                this.role = ''
                this.nocode= false
            }
        }).catch(error =>{
            this.pendingRequest = false
            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", error.response.data.message, "error");
            }
        })
    },
  }
}
</script>
