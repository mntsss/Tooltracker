<template>
    <modal name="change-password-modal"
           height="auto"
           :adaptive="true"
           transition="pop-out"
           :pivotY="0.3"
           :clickToClose="false"
           @before-close="beforeClose">
      <div class="card">
        <div class="card-header h5 secondary">
            Keisti slaptažodį <a @click="$modal.hide('change-password-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <v-form>
        <v-layout>
          <v-flex>
            <v-text-field v-model="oldpass" label="Senas slaptažodis" type="password" max="85" class="mx-2"></v-text-field>
            <v-text-field v-model="password" label="Naujas slaptažodis" type="password" max="85" class="mx-2"></v-text-field>
            <v-text-field v-model="password_confirmation" label="Pakartokite naują slaptažodį" type="password" max="85" class="mx-2"></v-text-field>
          </v-flex>
        </v-layout>
        <v-layout justify-center align-bottom>
          <v-flex shrink>
            <v-btn class="ma-2" @click="save()"><v-icon class="primary--text mr-3">fa-save</v-icon>Išsaugoti</v-btn>
          </v-flex>
        </v-layout>
        </v-form>
      </div>
    </modal>
</template>
<script>
import swal from 'sweetalert'
export default{
    data(){
        return{
            oldpass: '',
            password: '',
            password_confirmation: ''
        }
    },
    methods:{
        save: function(){
            this.$http.post('user/edit/password', {
                oldpass: this.oldpass,
                password: this.password,
                password_confirmation: this.password_confirmation
            }).then(response => {
                if(response.status == 200)
                {
                    swal(response.data.message, response.data.success, "success")
                    this.$modal.hide('change-password-modal')
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
        beforeClose: function(){
            this.oldpass = ''
            this.password = ''
            this.password_confirmation = ''
        }
    }
}
</script>
