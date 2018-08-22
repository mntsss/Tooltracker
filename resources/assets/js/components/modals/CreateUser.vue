<template>
  <modal name="create-user-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Pridėti vartotoją <a @click="$modal.hide('create-user-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form v-model="valid">
                <v-text-field v-model="email" :rules="emailRules" :counter="50" label="El paštas" required></v-text-field>
                <v-text-field v-model="name" :rules="nameRules" :counter="50" label="Vardas, pavardė" required></v-text-field>
                <v-select v-model="role" :items = "userRoles" :rules="roleRules" required></v-select>
                <v-text-field v-model="phone" :rules="phoneRules" :counter="14" label="Telefonas"></v-text-field>
                <v-checkbox v-model="nocode" label="Be identifikacinės kortelės"></v-checkbox>
                <v-btn @click="" :disabled="code == null && !nocode">Išsaugoti</v-btn>
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
            email: null,
            name: null,
            role: null,
            phone: null,
            code: null,
            nocode: false,
            /*emailRules: [
                v => !!v || "El. paštas būtinas!",
                v => v.length <= 50 || "El. pašto adresas per ilgas!"
            ],
            nameRules: [
                v => !!v || "Vartotojo vardas būtinas!",
                v => v.length <= 50 || "Vartotojo vardas negali viršyti 50 simbolių!"
            ],
            phoneRules: [ v => v.length <= 14 || "Įvestas per ilgas telefono numeris!"],
            roleRules: [ v => !!v || "Nenurodytas vartotojo tipas!"],
            userRoles: [ 'Darbuotojas', 'Administratorius']*/
        }
    },
    computed: {
        visibility: function(){
            return this.$children[0].visibility.modal
        },
        RFIDCode: function(){
            return this.$store.state.recentCode
        }
    },
    watch: {
        RFIDCode(oldRFIDCode, newRFIDCode){
            if(this.visibility){
                this.code = newRFIDCode
            }
        }
    },
  methods: {
    save: function(){
        /*var form = new FormData();

        form.append('name', this.name)
        form.append('image', this.image)
        if(this.code != null && this.code != "")form.append('code', this.code)
        if(this.consumable == true) form.append('consumable', 1)
        if(this.consumable == false) form.append('consumable', 0)
        if(this.warranty_date != null && this.warranty_date != "") form.append('warranty_date', this.format(this.warranty_date))
        if(this.purchase_date != null && this.purchase_date != "") form.append('purchase_date', this.format(this.purchase_date))
        form.append('groupID', this.groupID)

        this.$http.post('/item/create', form,
        {
            headers: {'Content-Type': 'multipart/form-data'}
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('create-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItems();
                this.name = null
                this.image = null
                this.code = null
                this.consumable = false
                this.warranty_date = ''
                this.purchase_date= ''
            }
        }).catch(error =>{

            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            if(error.response.status == 413)
            {
                swal("Klaida", "Failo dydis netinkamas!", "error");
            }
        })*/
    },
  }
}
</script>
