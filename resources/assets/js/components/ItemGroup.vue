<template>
  <div class="container">
    <RenameModal></RenameModal>
    <ChangeImageModal></ChangeImageModal>
    <CreateItemModal></CreateItemModal>
    <div class="card">
      <div class="card-header">
              <a @click="$router.go(-1)"><h4 class="fa fa-arrow-left text-primary remove-all-margin p-2 nav-arrow"></h4></a>

              <div class="dropdown show">
                <a class="fas fa-ellipsis-v text-primary btn-func-misc ml-2 mr-2 mb-0 mt-0" id="dropdownGroupFunc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownGroupFunc">
                  <a class="dropdown-item cursor-pointer" @click="show('rename-group-modal')">Pervadinti</a>
                  <a class="dropdown-item cursor-pointer" @click="show('change-group-image-modal')">Keisti nuotrauką</a>
                  <a class="dropdown-item cursor-pointer" @click="deleteGroup">Ištrinti</a>
                </div>
              </div>
              <a @click="show('create-item-modal')"><h4 class="fas fa-plus text-primary p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></h4></a>
              <h4 class="text-dark text-center">{{itemGroup.ItemGroupName}}</h4>


      </div>
      <div class="card-body">
        <div class="row remove-side-margin" v-for="item in items">
          <div class="col-6">
            {{item.item.ItemName}}
          </div>
          <div class="col text-center">
            {{item.state}}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import RenameModal from './modals/RenameGroup.vue'
import ChangeImageModal from './modals/ChangeGroupImage.vue'
import CreateItemModal from './modals/CreateItem.vue'
import swal from 'sweetalert'
  export default {
    data(){
      return {
        items: [],
        itemGroup: null
      }
    },
    props: {
        group: {
            required: true,
            type: Object
        }
    },
    created(){
        this.itemGroup = this.group;
        this.$http.get('/item/list/'+this.itemGroup.ItemGroupID).then((response)=>{
            if(response.status==200){
                this.items = response.data;
            }
        }).catch(error => {
          swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
        })
    },
    methods: {
        show (name) {
            this.$modal.show(name, {groupID: this.itemGroup.ItemGroupID});
        },
        deleteGroup: function(){
            swal({
              title: 'Ar tikrai norite ištrinti šią grupę?',
              text: 'Ištrinti galima tik grupes, kurios neturi naudojamų, rezervuotų ar taisomų įrankių. Ištrynus grupę, visi jai priskirti įrankiai taip pat bus ištrinti.',
              icon: 'warning',
              dangerMode: true,
              buttons: {
                del: { text: 'Trinti', value: true},
                cancel: {text: 'Atšaukti'}
              }
            }).then(value => {
              if(value){
                this.$http.get('/group/delete/'+this.itemGroup.ItemGroupID).then((response)=>{
                    if(response.status == 200){
                        swal(response.data.message, response.data.success, "success").then(value => { this.$router.push({name: 'main'})})
                    }
                }).catch(error =>{
                    if(error.response.status == 422)
                    {
                        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                    }
                })
              }
            })
        }
    },
    components: {
      RenameModal,
      ChangeImageModal,
      CreateItemModal
    }
}
</script>
