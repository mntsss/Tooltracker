<template>
<div class="loading-parent">
    <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <RenameItemModal></RenameItemModal>
        <ChangeItemIdnumberModal></ChangeItemIdnumberModal>
        <AddItemChipModal></AddItemChipModal>
    <div class="container">

    <div class="card" v-if="item">
      <v-layout row wrap align-content-center class="card-header pb-0 pt-0 mx-0 theme--dark v-toolbar">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="back()" class="headline"><span class="fa fa-arrow-left text-danger remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline">{{item.ItemName}}</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center>
              <v-menu offset-y>
                <a slot="activator" class="headline"><span class="fas fa-ellipsis-v text-danger p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
                <v-list>
                  <v-list-tile v-for="(item, index) in dropdownMeniu" :key="index" @click="item.click">
                    <v-list-tile-title>{{item.text}}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>
          </v-flex>
      </v-layout>
      <div class="card-body bg-dark">
        <v-container>
            <v-layout>
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
            </v-layout>
            <v-layout v-if="item.images[0]">
                <v-flex shrink>
                    <vueImages :imgs="images"
                            :modalclose="true"
                            :keyinput="true"
                            :showcaption="true"
                            :showclosebutton="true" class="image">
                    </vueImages>
                </v-flex>
            </v-layout>
        </v-container>
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
import RenameItemModal from '../modals/RenameItem.vue'
import ChangeItemIdnumberModal from '../modals/ChangeItemIdnumber.vue'
import AddItemChipModal from '../modals/AddItemChip.vue'
  export default {
    data(){
      return {
          item: '',
          itemStatus: '',
          images: [],

          isLoading: true,
          fullPage: false,
          dropdownMeniu: [
            {text: 'Priskirti čipą', click: () =>{this.show('add-item-chip-modal')}},
            {text: 'Pervadinti', click: ()=>{this.show('rename-item-modal')}},
            {text: 'Keisti identifikacinį numerį', click: ()=>{this.show('change-item-idnumber-modal')}},
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
      console.log(this.itemProp)
    if(this.itemProp == null){
      this.loadItem()
    }
    else {
      this.item =  this.itemProp.item
      this.itemStatus = this.itemProp.state
      if(this.item.images.length == 0){
          this.images.push({imageUrl: '/media/default_picture.png', caption: this.item.ItemName})
      }
      else
      {
          this.item.images.forEach(image => {
              this.images.push({imageUrl: this.$uploadPath+image.ImageName, caption: image.created_at})
          })
      }

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
                this.images = [];
                if(this.item.images.length == 0){
                    this.images.push({imageUrl: '/media/default_picture.png', caption: this.item.ItemName})
                }
                else
                {
                    this.item.images.forEach(image => {
                        this.images.push({imageUrl: this.$uploadPath+image.ImageName, caption: image.created_at})
                    })
                }
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
    back: function(){
        var previousRoute = this.$store.state.routesHistory[0].route
        var previousRouteParams = this.$store.state.routesHistory[0].params
        this.$router.push({name:previousRoute, params:previousRouteParams})
    }
  },
  components: {
      vueImages,
      Loading,
      RenameItemModal,
      ChangeItemIdnumberModal,
      AddItemChipModal
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
