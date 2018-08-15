<template>
  <modal name="create-group-modal"
         :height="300"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3">
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
                             <input name="image" type="file" id="image" ref="image" v-on:change="handleFileUpload" class="form-control" accept="image/*">
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
export default {
    data(){
        return {
            name: null,
            image: null
        }
    },
  methods: {
    save: function(){
        var form = new FormData();

        form.append('name', this.name)
        form.append('image', this.image)

        this.$http.post('/group/create', form,
        {
            headers: {'Content-Type': 'multipart/form-data'}
        }).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('create-group-modal')
                swal(response.data.message, response.data.success, "success")
            }
        }).catch(error =>{
            if(error.response.status == 422)
            {
                this.$modal.hide('create-group-modal')
                swal(error.response.data.message, error.response.data.errors.name[0], "error");
            }
        })
    },
    handleFileUpload: function(){
        this.image = this.$refs.image.files[0];
    }
  }
}
</script>
