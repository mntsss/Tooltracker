<template>
  <modal name="change-group-image-modal"
         :height="180"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Keisti nuotrauką
        </div>
        <div class="card-body">
            <form method="post" @submit.prevent="save">
                <div class="form-group">
                    <input class="form-control" type="file" name="image" ref="image" v-on:change="handleFileUpload" placeholder="Nauja nuotrauka" accept="image/*"/>
                </div>
                <div class="form-group d-flex content-align-center w-100">
                    <button class="btn btn-primary" type="submit">Išsaugoti</button>
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
            image: null,
            groupID: null
        }
    },
  methods: {
    save: function(){
        var form = new FormData();

        form.append('id', this.groupID)
        form.append('image', this.image)

        this.$http.post('/group/image', form,
        {
            headers: {'Content-Type': 'multipart/form-data'}
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
    handleFileUpload: function(){
        this.image = this.$refs.image.files[0];
    }
  }
}
</script>
