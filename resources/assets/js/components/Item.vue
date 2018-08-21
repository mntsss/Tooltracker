<template>
<div class="loading-parent">
    <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <RenameItemModal></RenameItemModal>
        <ChangeItemImageModal></ChangeItemImageModal>
    <div class="container">

    <div class="card">
      <v-layout row wrap align-content-center class="card-header pb-0 pt-0">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="$router.push({ path: '/group/'+item.ItemGroupID})" class="headline"><span class="fa fa-arrow-left text-primary remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-dark text-center headline">{{item.ItemName}}</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center>
              <v-menu offset-y>
                <a slot="activator" class="headline"><span class="fas fa-ellipsis-v text-primary p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
                <v-list>
                  <v-list-tile v-for="(item, index) in dropdownMeniu" :key="index" @click="item.click">
                    <v-list-tile-title>{{item.text}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
          </v-flex>
      </v-layout>
      <div class="card-body">
        <div class="row remove-side-margin">
            <div class="col-auto">
                <vueImages :imgs="images"
                            :modalclose="modalclose"
                            :keyinput="keyinput"
                            :showclosebutton="showclosebutton" class="image">
                </vueImages>
            </div>
            <div class="colt">
                <p class="text-left h4">
                    <span class="fab fa-ethereum"></span>&nbsp;Būsena:&nbsp;{{itemStatus}}
                </p>
                <p class="text-left h4" v-if="item.ItemPurchase != null">
                    <span class="fas fa-calendar-alt"></span>&nbsp;Įsigijimo data:&nbsp;{{item.ItemPurchase}}
                </p>
                <p class="text-left h4" v-if="item.ItemWarranty != null">
                    <span class="fas fa-calendar-alt"></span>&nbsp;Garantinis iki:&nbsp;{{item.ItemWarranty}}
                </p>
                <p class="text-left h4" v-if="item.ItemConsumable">
                    <span class="fas fa-check"></span>&nbsp;Suvartojama
                </p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import vueImages from 'vue-images'
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import RenameItemModal from './modals/RenameItem.vue'
import ChangeItemImageModal from './modals/ChangeItemImage.vue'
  export default {
    data(){
      return {
          item: '',
          itemStatus: '',
          images: [],
          modalclose: true,
          keyinput: true,
          mousescroll: true,
          showclosebutton: true,
          showcaption: true,
          isLoading: true,
          fullPage: false,
          dropdownMeniu: [
            {text: 'Priskirti čipą', click: () =>{}},
            {text: 'Pervadinti', click: ()=>{this.show('rename-item-modal')}},
            {text: 'Keisti nuotrauką', click: ()=>{this.show('change-item-image-modal')}},
            {text: 'Ištrinti', click: ()=>{this.deleteItem()}}
          ]
      }
  },
  props: {
      itemProp: {
          required: false,
          type: Object
      }
  },
  created(){
    if(this.itemProp == null){
      this.loadItem()
    }
    else {
      this.item =  this.itemProp.item
      this.itemStatus = this.itemProp.state
      if(this.item.ItemImage == null || this.item.ItemImage == "null" || this.item.ItemImage == "")
        this.images.push({imageUrl: '/media/default_picture.png', caption: this.item.ItemName})
      else
        this.images.push({imageUrl: '/media/uploads/'+this.item.ItemImage, caption: this.item.ItemName})
    }
  },
  mounted(){
    this.isLoading = false
  },
  methods: {
    show: function(name){
      this.$modal.show(name, {itemID: this.item.ItemID})
    },
    loadItem: function(){
            //this.isLoading = true
        return this.$http.get('/item/get/'+this.item.ItemID).then((response)=>{
            if(response.status == 200){
                this.itemStatus = response.data.state
                this.item = response.data.item
                this.images =[];
                if(this.item.ItemImage == null || this.item.ItemImage == "null" || this.item.ItemImage == "")
                  this.images.push({imageUrl: '/media/default_picture.png', caption: this.item.ItemName})
                else
                  this.images.push({imageUrl: '/media/uploads/'+this.item.ItemImage, caption: this.item.ItemName})
            }
        }).catch(error => {
            if(error.response.status == 422){
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                this.isLoading = false
            }
        })
    },
    deleteItem: function(){
        swal({
          title: 'Ar tikrai norite ištrinti šį įrankį?',
          text: 'Ištrinti galima tik įrankius, kurie nėra naudojami, rezervuoti ar taisomi.',
          icon: 'warning',
          dangerMode: true,
          buttons: {
            del: { text: 'Trinti', value: true},
            cancel: {text: 'Atšaukti'}
          }
        }).then(value => {
          if(value){
            this.$http.get('/item/delete/'+this.item.ItemID).then((response)=>{
                if(response.status == 200){
                    swal(response.data.message, response.data.success, "success").then(value => { this.$router.push({ path: '/group/'+this.item.ItemGroupID})})
                }
            }).catch(error =>{
                if(error.response.status == 422)
                {
                    swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                }
            })
          }
        })
    },
  },
  components: {
      vueImages,
      Loading,
      RenameItemModal,
      ChangeItemImageModal
  }
}
</script>
<style>
    .image{
        width: 100%;
        height: auto;
        overflow: hidden;
    }
    .loading-parent{
        position: relative;
    }
    </style>
