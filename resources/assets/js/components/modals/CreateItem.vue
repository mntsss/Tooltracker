<template>
  <modal name="create-item-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header theme--dark v-toolbar headline text-center">
            Pridėti įrankį <a @click="$modal.hide('create-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body bg-dark text-light">
            <v-form>
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label">Pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" v-model="name" maxlength="45" required autofocus>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label">Įsigijimo data</label>

                    <div class="col-md-6">
                        <DatePicker v-model="purchase_date" :lang="lang" format="YYYY-MM-DD"></DatePicker>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label ">Garantinis iki</label>

                    <div class="col-md-6">
                        <DatePicker v-model="warranty_date" :lang="lang" format="YYYY-MM-DD"></DatePicker>
                    </div>
                </div>
                <div class="form-group row remove-side-margin">
                  <label class="col-auto control-label mb-4" for="consumable">
                    Suvartojama
                  </label>
                  <div class="col align-left">
                    <input class="remove-all-margin" type="checkbox" value="" id="consumable" v-model="consumable" style="height:23px; width:23px">
                  </div>
                </div>
                <div class="form-group mb-4">
                        <label for="image" class="col-md-4 control-label">Nuotrauka</label>

                        <div class="col-md-6 text-right">
                             <input name="image" type="file" id="image" ref="image" v-on:change="handleFileUpload" class="form-control" accept="image/*">
                        </div>
                </div>
                <v-layout row mx-0 align-center justify-center pa-3>
                  <v-flex shrink v-if="code && !nocode">
                    <span class="text-success headline">Identifikacinis čipas nuskaitytas</span>
                  </v-flex>
                  <v-flex shrink v-else-if="!code && !nocode">
                    <span class="text-danger headline">Laukiama identifikacinio čipo...</span>
                  </v-flex>
                </v-layout>
                <div class="form-group row remove-side-margin">
                  <label class="col-auto control-label mb-4" for="consumable">
                    Be identifikacinio čipo
                  </label>
                  <div class="col align-left">
                    <input class="remove-all-margin" type="checkbox" v-model="nocode" style="height:23px; width:23px">
                  </div>
                </div>
                <v-layout row mx-0 pa-2 align-center justify-center>
                  <v-flex shrink>
                    <v-btn outline color="blue" :disabled="!code && !nocode" @click="save()">
                            Pridėti
                        </v-btn>
                  </v-flex>
                </v-layout>
            </v-form>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
import DatePicker from 'vue2-datepicker'
export default {
    data(){
        return {
            name: null,
            image: null,
            code: null,
            nocode: false,
            consumable: false,
            warranty_date: '',
            purchase_date: '',
            groupID: null,
            lang: {
              days: ['Pr', 'An', 'Tr', 'Ket', 'Pn', 'Še', 'Sek'],
              months: ['Sau', 'Vas', 'Kov', 'Bal', 'Geg', 'Bir', 'Lie', 'Rugp', 'Rugs', 'Spa', 'Lap', 'Gru'],
              placeholder: {
                date: 'Pasirinkite datą',
                dateRange: 'Pasirinkite laikotarpį'
              }
            }
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
            if(this.visibility && this.RFIDCode){
                this.code = this.RFIDCode;
                this.$store.commit('resetCode')
            }
        }
    },
  methods: {
    save: function(){
        var form = new FormData();

        form.append('name', this.name)
        form.append('image', this.image)
        if(this.code != null && this.code != "")form.append('code', this.code)
        if(this.consumable == true) form.append('consumable', 1)
        if(this.consumable == false) form.append('consumable', 0)
        if(this.nocode == true) form.append('nocode', 1)
        if(this.nocode == false) form.append('nocode', 0)
        if(this.warranty_date != null && this.warranty_date != "") form.append('warranty_date', this.format(this.warranty_date))
        if(this.purchase_date != null && this.purchase_date != "") form.append('purchase_date', this.format(this.purchase_date))
        form.append('groupID', this.groupID)
        if(this.nocode)form.append('code', '')
        this.$http.post('/item/create', form,
        {
            headers: {'Content-Type': 'multipart/form-data'}
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('create-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItems();
            }
        }).catch(error =>{

            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            else if(error.response.status == 413)
            {
                swal("Klaida", "Failo dydis netinkamas!", "error");
            }
            else{
                swal("Klaida", error.response.data.message, "error");
            }
        })
    },
    handleFileUpload: function(){
        this.image = this.$refs.image.files[0];
    },
    beforeOpen: function(event){
      this.groupID = event.params.groupID
    },
    beforeClose: function(){
      this.name = null
      this.image = null
      this.code = null
      this.consumable = false
      this.nocode= false
      this.warranty_date = ''
      this.purchase_date= ''
    },
    format: function(date){
      if(date == null || date == "") return null
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }
  },
  components: {
    DatePicker
  }
}
</script>
