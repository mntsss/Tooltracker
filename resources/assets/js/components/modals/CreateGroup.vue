<template>
  <modal name="create-group-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
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
        <div class="card-header theme--primary v-toolbar">
            Sukurti grupę<a @click="$modal.hide('create-group-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body h-100">
            <form>
                <div class="form-group">
                    <label for="name" class="col-md-4 control-label">Pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" v-model="name" maxlength="40" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                        <label for="image" class="col-md-4 control-label">Nuotrauka</label>

                        <div class="col-md-6 text-right">
                          <image-uploader :debug="1" :maxWidth="192" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                            capture="environment" @input="setImage" @onUpload="loadingDialog" @onComplete="loadingDialog" class="form-control"></image-uploader>
                        </div>
                    </div>

                <v-layout align-center justify-center>
                    <v-flex shrink>
                      <v-progress-circular
                        indeterminate
                        color="primary"
                        v-if="pendingRequest"
                      ></v-progress-circular>
                        <v-btn v-else @click="save()">
                            <v-icon class="mr-2">fa-check</v-icon>Pridėti
                        </v-btn>
                    </v-flex>
                </v-layout>
            </form>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
import { ImageUploader } from 'vue-image-upload-resize'
export default {
    data(){
        return {
            imageLoadingDialog: false,
            hasImage: false,
            name: null,
            image: null,
            pendingRequest: false
        }
    },
  methods: {
    save: function(){
        this.pendingRequest = true
        this.$http.post('/group/create', { image: this.image, name: this.name})
        .then((response)=>{
          this.pendingRequest = false
            if(response.status == 200){
                this.$modal.hide('create-group-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadGroups();

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
    beforeClose: function(){
      this.name = null
      this.image = null
    }
  },
  components: {
    ImageUploader
  }
}
</script>
