<template>
  <modal name="create-item-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
         <v-dialog v-model="imageLoadingDialog" hide-overlay persistent width="300">
           <v-card class="border border-danger">
             <v-card-text>
               Kraunama...
               <v-progress-linear indeterminate color="primary" class="mb-0"></v-progress-linear>
             </v-card-text>
           </v-card>
         </v-dialog>
    <div class="card">
        <div class="card-header theme--primary v-toolbar headline text-center">
            Pridėti įrankį <a @click="$modal.hide('create-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
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
                        <DatePicker v-on:changed="purchase_changed"></DatePicker>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label ">Garantinis iki</label>

                    <div class="col-md-6">
                      <DatePicker v-on:changed="warranty_changed"></DatePicker>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="number" class="col-md-4 control-label">Identifikacinis numeris</label>

                    <div class="col-md-6">
                        <input id="idnumber" type="text" class="form-control" name="idnumber" v-model="idnumber" maxlength="190">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="number" class="col-md-4 control-label">Įsigyta iš</label>

                    <div class="col-md-6">
                        <input id="acquired" type="text" class="form-control" name="idnumber" v-model="acquired" maxlength="150">
                    </div>
                </div>

                <div class="form-group row mx-0 mx-0">
                  <label class="col-auto control-label mb-4" for="consumable">
                    Suvartojama
                  </label>
                  <div class="col align-left">
                    <input class="remove-all-margin" type="checkbox" value="" id="consumable" v-model="consumable" style="height:23px; width:23px">
                  </div>
                </div>
<!--                <div class="form-group mb-4">
                        <label for="image" class="col-md-4 control-label">Nuotrauka</label>

                        <div class="col-md-6 text-right">
                            <image-uploader :debug="1" :maxWidth="192" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput&#45;&#45;loaded' : hasImage }]"
                              capture="environment" @input="setImage" @onUpload="loadingDialog" @onComplete="loadingDialog" class="form-control"></image-uploader>
                        </div>
                </div>-->
<!--                <v-layout row mx-0 mx-0 align-center justify-center pa-3>
                  <v-flex shrink v-if="code && !nocode">
                    <span class="text-success headline">Identifikacinis čipas nuskaitytas</span>
                  </v-flex>
                  <v-flex shrink v-else-if="!code && !nocode">
                    <span class="text-danger headline">Laukiama identifikacinio čipo...</span>
                  </v-flex>
                </v-layout>-->
<!--                <div class="form-group row mx-0 remove-side-margin">
                  <label class="col-auto control-label mb-4" for="consumable">
                    Be identifikacinio čipo
                  </label>
                  <div class="col align-left">
                    <input class="remove-all-margin" type="checkbox" v-model="nocode" style="height:23px; width:23px">
                  </div>
                </div>-->
                <v-layout row mx-0 mx-0 pa-2 align-center justify-center>
                  <v-flex shrink>
                    <v-progress-circular
                      indeterminate
                      color="primary"
                      v-if="pendingRequest"
                    ></v-progress-circular>
                    <v-btn v-else outline color="primary" :disabled="!code && !nocode" @click="save()">
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
import DatePicker from '../../modules/DatePicker.vue'
import { ImageUploader } from 'vue-image-upload-resize'
export default {
    data(){
        return {
            imageLoadingDialog: false,
            hasImage: false,

            name: null,
            image: null,
            code: null,
            nocode: true,
            acquired: null,
            idnumber: null,
            consumable: false,
            warranty_date: '',
            purchase_date: '',
            groupID: null,
            pendingRequest: false
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
      this.pendingRequest = true
        this.$http.post('/item/create',
        {
            name: this.name,
            image: this.image,
            code: this.code,
            identification: this.idnumber,
            acquired_from: this.acquired,
            consumable: this.consumable,
            nocode: this.nocode,
            warranty_date: this.format(this.warranty_date),
            purchase_date: this.format(this.purchase_date),
            group_id: this.groupID,
        }
      ).then((response)=>{
        this.pendingRequest = false
            if(response.status == 200){
                this.$modal.hide('create-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItems();
            }
        }).catch(error =>{
          this.pendingRequest = false
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
    setImage: function(file){
        this.hasImage = true
        this.image = file
    },
    loadingDialog: function(){
      this.imageLoadingDialog = !this.imageLoadingDialog
    },
    beforeOpen: function(event){
      this.groupID = event.params.groupID
    },
    beforeClose: function(){
      this.name = null
      this.image = null
      this.code = null
      this.consumable = false
      this.acquired = null
      this.warranty_date = ''
      this.purchase_date= ''
      this.idnumber = null
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
    },
    purchase_changed: function(event){
      this.purchase_date = event.date
    },
    warranty_changed: function(event){
      this.warranty_date = event.date
    }
  },
  components: {
    DatePicker,
    ImageUploader
  }
}
</script>
