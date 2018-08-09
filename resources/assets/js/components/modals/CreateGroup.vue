<template>
  <div id="categoryAdd" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title danger">Pridėti įrankių grupę</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" method="POST" @submit.prevent="createGroup" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="name" class="col-md-4 control-label">Pavadinimas</label>

                  <div class="col-md-6">
                      <input id="name" type="name" class="form-control" name="name" v-model="itemGroupName" maxlength="25" required autofocus>
                  </div>
              </div>

              <div class="form-group">
                      <label for="image" class="col-md-4 control-label">Nuotrauka</label>

                      <div class="col-md-6 text-right">
                           <input name="image" type="file" id="image" ref="image" v-on:change="handleFileUpload" class="form-control" accept="image/*">
                      </div>
                  </div>

              <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                      <button type="submit" class="btn btn-primary">
                          Pridėti
                      </button>
                  </div>
              </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>
<script>
export default{
  data(){
    return {
      itemGroupName: null,
      itemGroupImage: null
    }
  },
  methods: {
    createGroup: function(){
      let formData = new FormData();

      formData.append('image', this.itemGroupImage);
      formData.append('name', this.itemGroupName);
      this.$http.post('/group/create',
          formData,
          {
              headers: {'Content-Type': 'multipart/form-data'}
          }).then((response)=>{
            if(response.status == 200){
              alert('success!');
              this.$parent.loadGroups();
            }
          })
    },
    handleFileUpload: function(){
      this.itemGroupImage = this.$refs.image.files[0];
    }
  }
}
</script>
