<template>
  <div class="container">
    <RenameModal></RenameModal>
    <ChangeImageModal></ChangeImageModal>
    <DeleteModal></DeleteModal>
    <div class="card">
      <div class="card-header">
              <a @click="$router.go(-1)"><h4 class="fa fa-arrow-left text-primary remove-all-margin p-2 nav-arrow"></h4></a>
              <div class="dropdown show">
                <a class="fas fa-ellipsis-v text-primary remove-all-margin btn-func-misc" id="dropdownGroupFunc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <!-- <span class="fas fa-ellipsis-v text-primary remove-all-margin btn-func-misc"></span> -->
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownGroupFunc">
                  <a class="dropdown-item cursor-pointer" @click="show('rename-group-modal')">Pervadinti</a>
                  <a class="dropdown-item cursor-pointer" @click="show('change-group-image-modal')">Keisti nuotrauką</a>
                  <a class="dropdown-item cursor-pointer" @click="deleteGroup">Ištrinti</a>
                </div>
              </div>
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
import DeleteModal from './modals/DeleteConfirmationModal.vue'
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
            else{
                console.log('error')
            }
        })
    },
    methods: {
        show (name) {
            this.$modal.show(name, {groupID: this.itemGroup.ItemGroupID});
        },
        hide (name) {
            this.$modal.hide(name);
        },
        deleteGroup: function(){
            this.$modal.show('delete-confirm-modal',
            {
                header: 'Ar tikrai norite ištrinti šią grupę?',
                message: 'Ištrinti galima tik grupes, kurios neturi naudojamų, rezervuotų ar taisomų įrankių. Ištrynus grupę, visi jai priskirti įrankiai taip pat bus ištrinti.',
                url: '/group/delete/'+this.itemGroup.ItemGroupID
            })
        }
    },
    components: {
      RenameModal,
      ChangeImageModal,
      DeleteModal
    }
}
</script>
