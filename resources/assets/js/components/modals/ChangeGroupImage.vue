<template>
  <modal name="change-group-image-modal"
         :height="180"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
         <v-dialog v-model="imageLoadingDialog" hide-overlay persistent width="300">
           <v-card class="border border-dark">
             <v-card-text>
               Kraunama...
               <v-progress-linear indeterminate color="primary" class="mb-0"></v-progress-linear>
             </v-card-text>
           </v-card>
         </v-dialog>
    <div class="card">
        <div class="card-header">
            Keisti nuotrauką
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                  <image-uploader :debug="1" :maxWidth="192" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                    capture="environment" @input="setImage" @onUpload="loadingDialog" @onComplete="loadingDialog" class="form-control"></image-uploader>
                </div>
                <v-layout align-center justify-center>
                    <v-flex shrink>
                        <v-btn class="primary text-white" @click="save()"><v-icon class="text-white pr-3">fa-save</v-icon>Išsaugoti</v-btn>
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
            image: null,
            groupID: null,
            imageLoadingDialog: false,
            hasImage: false,
        }
    },
  methods: {
    save: function(){
        this.$http.post('/group/image',
        {
            id: this.groupID,
            image: this.image
        }).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('change-group-image-modal')
                swal(response.data.message, response.data.success, "success")
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
    beforeOpen: function(event){
        this.groupID = event.params.groupID
    },
    beforeClose: function(event){
      this.groupID = null
      this.image = null
    },
    setImage: function(file){
        this.hasImage = true
        this.image = file
    },
    loadingDialog: function(){
      this.imageLoadingDialog = !this.imageLoadingDialog
    },
  },
  components: {
    ImageUploader
  }
}
</script>
