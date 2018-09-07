<template>
<div class="loading-parent">
    <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <RenameItemModal></RenameItemModal>
        <ChangeItemIdnumberModal></ChangeItemIdnumberModal>
        <AddItemChipModal></AddItemChipModal>
        <ItemWarrantyFixModal></ItemWarrantyFixModal>
        <ItemUnwarrantedFixModal></ItemUnwarrantedFixModal>
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
            <v-layout mb-3>
              <v-card tile width="100%">
                  <v-card-text>
                      <v-layout row align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-map-marker</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Būsena:</v-flex>
                          <v-flex px-2 shrink>{{itemStatus}}</v-flex>
                          <v-flex v-if="itemStatus == 'Naudojamas' && item.last_withdrawal.object">({{item.last_withdrawal.object.ObjectName}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Naudojamas' && item.last_withdrawal.user">({{item.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Laukia patvirtinimo' && item.last_withdrawal.user">({{item.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Ištrintas'"><v-btn icon class="text-warning px-3"><v-icon>fa-undo</v-icon></v-btn></v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="item.ItemIdNumber">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-fingerprint</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Identifikacinis numeris:</v-flex>
                          <v-flex px-2>{{item.ItemIdNumber}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-calendar-plus</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Pridėjimo data:</v-flex>
                          <v-flex px-2>{{item.created_at}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="item.ItemPurchase">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-calendar-alt</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Įsigijimo data:</v-flex>
                          <v-flex px-2>{{item.ItemPurchase}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="item.ItemWarranty">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-calendar-check</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Garantinis iki:</v-flex>
                          <v-flex px-2>{{item.ItemWarranty}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="item.ItemConsumable">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-check</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Suvartojama</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center>
                        <v-flex pa-2 xs10>
                          <v-textarea
                            name="note"
                            :disabled = 'readonly'
                            box
                            label="Įrankio prierašas"
                            auto-grow
                            v-model= "note"
                          ></v-textarea>
                        </v-flex>
                        <v-flex pa-2 shrink>
                          <v-btn icon v-if="readonly" @click="readonly = false"><v-icon class="text-warning">fa-edit</v-icon></v-btn>
                          <v-btn icon v-if="!readonly" @click="editNote()"><v-icon class="text-warning">fa-save</v-icon></v-btn>
                        </v-flex>
                      </v-layout>
                      <v-layout row wrap align-center pa-2 justify-end>
                          <v-flex shrink justify-end>
                            <v-btn outline v-if="!item.ItemDeleted && warrantyFix && itemStatus == 'Sandėlyje'" @click="show('item-warranty-fix-modal')">
                                <v-icon class="text-danger">fa-wrench</v-icon>
                                <span class="mx-2">Garantinis taisymas</span>
                            </v-btn>
                            <v-btn outline v-if="!item.ItemDeleted && itemStatus == 'Sandėlyje'" @click="show('item-unwarranted-fix-modal')">
                                <v-icon class="text-danger">fa-wrench</v-icon>
                                <span class="mx-2">Taisymas</span>
                            </v-btn>
                            <v-btn outline @click="show('edit-user-modal', {user: user})" v-if="!item.ItemDeleted && itemStatus == 'Sandėlyje'">
                                  <v-icon class="text-danger">fa-user-tag</v-icon>
                                  <span class="mx-2">Priskirti vartotojui</span>
                              </v-btn>
                              <v-btn outline @click="show('add-user-card-modal', {id: user.UserID})">
                                  <v-icon class="text-danger">fa-history</v-icon>
                                  <span class="mx-2">Istorija</span>
                              </v-btn>
                          </v-flex>
                      </v-layout>
                  </v-card-text>
              </v-card>
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
import ItemWarrantyFixModal from '../modals/ItemWarrantyFix.vue'
import ItemUnwarrantedFixModal from '../modals/ItemUnwarrantedFix.vue'
  export default {
    data(){
      return {
          item: '',
          itemStatus: '',
          images: [],
          note: '',
          readonly: true,
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
    if(this.itemProp == null){
      this.loadItem()
    }
    else {
      this.item =  this.itemProp.item
      this.itemStatus = this.itemProp.state
      this.note = this.item.ItemNote
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
  computed: {
    warrantyFix: function(){
      if(!this.item.ItemWarranty)
        return false
      else{
        var now = new Date()
        console.log(now)
        var formatedWarranty = new Date(this.item.ItemWarranty)
        console.log(formatedWarranty)
        if(now.getTime() < formatedWarranty.getTime())
          return true
        else
          return false
      }
    }
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
                this.note = this.item.note
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
    editNote: function(){
      this.$http.post('/item/edit/note', {id: this.item.ItemID, note: this.note})
      .then(response => {
        if(response.status == 200)
        {
          this.readonly = true
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
      AddItemChipModal,
      ItemWarrantyFixModal,
      ItemUnwarrantedFixModal
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
