<template>
  <div class="container" style="min-height: 70vh !important" v-if="itemGroup">
    <RenameModal :groupID='itemGroup.ItemGroupID'></RenameModal>
    <ChangeImageModal></ChangeImageModal>
    <CreateItemModal></CreateItemModal>
    <div class="card">
      <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary" v-if="items">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline" v-if="itemGroup">{{itemGroup.ItemGroupName}}</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center v-if="$user.UserRole =='Administratorius'">
              <a @click="show('create-item-modal')" class="headline"><span class="fas fa-plus primary--text p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
              <v-menu offset-y>
                <a slot="activator" class="headline"><span class="fas fa-ellipsis-v primary--text p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
                <v-list>
                  <v-list-tile v-for="(item, index) in dropdownMeniu" :key="index" @click="item.click">
                    <v-list-tile-title>{{item.text}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="items.length > 0">
        <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer" :to="{ name: 'item', params: { itemID: item.id}}" v-for="item in items" :key="item.id">
          <div class="col-6">
            {{item.name}}
          </div>
          <div class="col text-center">
            {{item.status}}
          </div>
      </router-link>
      </div>
      <div class="card-body mt-1 border border-dark" v-else-if="items.length == 0">
        <div class="text-center h5 pa-5">
          Grupėje įrankių nėra...
        </div>
      </div>
    </div>
  </div>

</template>
<script>

import RenameModal from '../modals/RenameGroup.vue'
import ChangeImageModal from '../modals/ChangeGroupImage.vue'
import CreateItemModal from '../modals/item/CreateItem.vue'
import swal from 'sweetalert'

  export default {
    data(){
      return {
        items: [],
        itemGroup: null,
        fullPage: false,
        dropdownMeniu: [
          {text: 'Pervadinti', click: ()=>{this.show('rename-group-modal')}},
          {text: 'Keisti nuotrauką', click: ()=>{this.show('change-group-image-modal')}},
          {text: 'Ištrinti', click: ()=>{this.deleteGroup()}}
        ]
      }
    },
    props: {
        group: {
            required: true,
            type: String
        }
    },
    async created(){
        this.loadGroup().then(this.loadItems)
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
                        swal(response.data.message, response.data.success, "success").then(value => { this.$router.push({name: 'groups'})})
                    }
                }).catch(error =>{
                    if(error.response.status == 422)
                    {
                        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                    }
                    else {
                      swal('Klaida', error.response.data.message, 'error')
                    }
                })
              }
            })
        },
        loadGroup: function(){
            return this.$http.get('/group/get/'+this.group).then((response)=>{
                if(response.status == 200){
                    this.itemGroup = response.data;
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        },
        loadItems: function(){
          return this.$http.get('/item/list/'+this.itemGroup.ItemGroupID).then((response)=>{
              if(response.status==200){
                  this.items = response.data;
                  this.$contentLoadingHide()
              }
          }).catch(error => {
            swal('Klaida', error.response.data.message, 'error')
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
<style>
    .loading-parent{
        position: relative;
    }
</style>
