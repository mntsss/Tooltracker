<template>
  <modal name="create-group-modal"
         :height="300"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-close="beforeClose">
    <v-dialog v-model="imageLoadingDialog" hide-overlay persistent width="300">
      <v-card dark class="border border-danger">
        <v-card-text>
          Kraunama...
          <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
    <div class="card">
        <div class="card-header bg-dark text-light">
            Sukurti grupę
        </div>
        <div class="card-body">
            <form class="" method="POST" @submit.prevent="save">
                <div class="form-group">
                    <label for="name" class="col-md-4 control-label text-dark">Pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" v-model="name" maxlength="25" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                        <label for="image" class="col-md-4 control-label text-dark">Nuotrauka</label>

                        <div class="col-md-6 text-right">
                          <image-uploader :debug="1" :maxWidth="192" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                            capture="environment" @input="setImage" @onUpload="loadingDialog" @onComplete="loadingDialog" class="form-control"></image-uploader>
                        </div>
                    </div>

                <div class="form-group d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">
                            Pridėti
                        </button>
                </div>
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
            image: null
        }
    },
  methods: {
    save: function(){
        this.$http.post('/group/create', { image: this.image, name: this.name})
        .then((response)=>{
            if(response.status == 200){
                this.$modal.hide('create-group-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadGroups();

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
