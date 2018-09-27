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
        <ChangeItemWarrantyModal></ChangeItemWarrantyModal>
        <RestoreItemModal></RestoreItemModal>
        <ItemReturnConfirmation v-on:reload="loadItem()"></ItemReturnConfirmation>
        <ConfirmReturnItemSuspentionModal></ConfirmReturnItemSuspentionModal>
        <ChangeItemAcquiredModal></ChangeItemAcquiredModal>
    <div class="container">

    <div class="card" v-if="itemData">
      <v-layout row wrap align-content-center class="card-header pb-0 pt-0 mx-0 secondary">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline">{{itemData.ItemName}}</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center v-if="$user.UserRole == 'Administratorius'">
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
      <div class="card-body">
        <v-container>
            <v-layout mb-3>
              <v-card tile width="100%">
                  <v-card-text>
                      <v-layout row align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-map-marker</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Būsena:</v-flex>
                          <v-flex px-2 shrink>{{itemStatus}}</v-flex>
                          <v-flex v-if="itemStatus == 'Naudojamas' && itemData.last_withdrawal.object">({{itemData.last_withdrawal.object.ObjectName}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Naudojamas' && itemData.last_withdrawal.user">({{itemData.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Laukia patvirtinimo' && itemData.last_withdrawal.user">({{itemData.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Ištrintas'"><v-btn icon class="text-warning px-3" @click="show('restore-item-modal')"><v-icon>fa-undo</v-icon></v-btn></v-flex>
                          <v-flex v-if="itemStatus == 'Taisomas' || itemStatus == 'Garantinis taisymas'"><v-btn outline class="mx-2" @click="fixed()"><v-icon class="primary--text pr-3">fa-check</v-icon>Sutaisyta</v-btn></v-flex>
                          <v-flex v-else-if="itemStatus == 'Naudojamas'"><v-btn outline class="mx-2" @click="returnItem()"><v-icon class="primary--text pr-3">fa-sign-in-alt</v-icon>Grąžinti į sandėlį</v-btn></v-flex>
                          <v-flex v-else-if="itemStatus == 'Laukia patvirtinimo'"><v-btn outline class="mx-2" @click="$modal.show('confirm-return-item-suspention-modal', {itemID: itemData.ItemID})"><v-icon class="primary--text pr-3">fa-check</v-icon>Patvirtinti grąžinimą</v-btn></v-flex>
                      </v-layout>
                      <v-layout row align-center v-if="itemData.last_suspention">
                          <v-flex pa-2 xs10 v-if="!itemData.last_suspention.SuspentionReturned && itemData.last_suspention.SuspentionNote">
                            <v-textarea
                              name="note"
                              :disabled = 'true'
                              box
                              label="Įšaldymo komentaras"
                              auto-grow
                              v-model= "itemData.last_suspention.SuspentionNote"
                            ></v-textarea>
                          </v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.ItemIdNumber">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-fingerprint</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Identifikacinis numeris:</v-flex>
                          <v-flex px-2>{{itemData.ItemIdNumber}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.ItemAcquiredFrom">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-shopping-bag</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Įsigyta iš:</v-flex>
                          <v-flex px-2>{{itemData.ItemAcquiredFrom}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-plus</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Pridėjimo data:</v-flex>
                          <v-flex px-2>{{itemData.created_at}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.ItemPurchase">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-alt</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Įsigijimo data:</v-flex>
                          <v-flex px-2>{{itemData.ItemPurchase}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.ItemWarranty">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-check</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Garantinis iki:</v-flex>
                          <v-flex px-2>{{itemData.ItemWarranty}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.ItemConsumable">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-check</v-icon>
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
                            <v-btn outline v-if="!itemData.ItemDeleted && warrantyFix && itemStatus == 'Sandėlyje' && !itemData.ItemConsumable" @click="show('item-warranty-fix-modal')">
                                <v-icon class="primary--text">fa-wrench</v-icon>
                                <span class="mx-2">Garantinis taisymas</span>
                            </v-btn>
                            <v-btn outline v-if="!itemData.ItemDeleted && itemStatus == 'Sandėlyje' && !itemData.ItemConsumable" @click="show('item-unwarranted-fix-modal')">
                                <v-icon class="primary--text">fa-wrench</v-icon>
                                <span class="mx-2">Taisymas</span>
                            </v-btn>
                              <v-btn outline @click="show('add-user-card-modal', {id: user.UserID})">
                                  <v-icon class="primary--text">fa-history</v-icon>
                                  <span class="mx-2">Istorija</span>
                              </v-btn>
                          </v-flex>
                      </v-layout>
                  </v-card-text>
              </v-card>
            </v-layout>
        <div v-if="itemData.images">
            <v-layout v-if="itemData.images[0]">
                <v-flex shrink>
                    <vueImages :imgs="images"
                            :modalclose="true"
                            :keyinput="true"
                            :showcaption="true"
                            :showclosebutton="true" class="image">
                    </vueImages>
                </v-flex>
            </v-layout>
        </div>
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
import ChangeItemAcquiredModal from '../modals/ChangeItemAcquiredFrom.vue'
import AddItemChipModal from '../modals/AddItemChip.vue'
import ItemWarrantyFixModal from '../modals/ItemWarrantyFix.vue'
import ItemUnwarrantedFixModal from '../modals/ItemUnwarrantedFix.vue'
import ItemReturnConfirmation from '../modals/ItemReturnConfirmation.vue'
import ChangeItemWarrantyModal from '../modals/ChangeItemWarranty.vue'
import RestoreItemModal from '../modals/RestoreItem.vue'
import ConfirmReturnItemSuspentionModal from '../modals/ConfirmReturnItemSuspention.vue'
  export default {
    data(){
      return {
          itemData: '',
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
            {text: 'Keisti įsigijimo vietą', click: ()=>{this.show('change-item-acquired-modal')}},
            {text: 'Keisti garantinį laikotarpį', click: ()=>{this.$modal.show('change-item-warranty-modal', {itemID: this.itemData.ItemID, warranty: this.itemData.ItemWarranty})}},
            {text: 'Ištrinti', click: ()=>{this.deleteItem()}},
          ]
      }
  },
  props: {
      itemProp: {
          required: false,
          type: Object
      },
      itemID:{
          required: false,
          type: Number
      }
  },
  created(){
    if(this.itemProp == null){
        if(this.itemID != null){
            this.itemData = {ItemID: this.itemID}
        }
        this.loadItem()
    }
    else {
      this.itemData =  this.itemProp
      if(this.itemProp.state == null){
          this.loadItem()
      }
      else {
          this.itemStatus = this.itemProp.state
          this.note = this.itemData.ItemNote
          if(this.itemData.images.length == 0){
              this.images.push({imageUrl: '/media/default_picture.png', caption: this.itemData.ItemName})
          }
          else
          {
              this.itemData.images.forEach(image => {
                  this.images.push({imageUrl: this.$uploadPath+image.ImageName, caption: image.created_at})
              })
          }
      }
    }
  },
  mounted(){
    this.isLoading = false
  },
  computed: {
    warrantyFix: function(){
      if(!this.itemData.ItemWarranty)
        return false
      else{
        var now = new Date()
        var formatedWarranty = new Date(this.itemData.ItemWarranty)
        if(now.getTime() < formatedWarranty.getTime())
          return true
        else
          return false
      }
    }
  },
  methods: {
    show: function(name){
      this.$modal.show(name, {itemID: this.itemData.ItemID})
    },
    returnItem: function(){
        var item = this.itemData
        item.last_withdrawal.image = item.images[0]
        this.$modal.show('item-return-confirm-modal', {item: item})
    },
    loadItem: function(){
            //this.isLoading = true
        return this.$http.get('/item/get/'+this.itemData.ItemID).then((response)=>{
            if(response.status == 200){
                this.itemStatus = response.data.state
                this.itemData = response.data
                this.images = [];
                this.note = this.itemData.note
                if(this.itemData.images.length == 0){
                    this.images.push({imageUrl: '/media/default_picture.png', caption: this.itemData.ItemName})
                }
                else
                {
                    this.itemData.images.forEach(image => {
                        this.images.push({imageUrl: this.$uploadPath+image.ImageName, caption: image.created_at})
                    })
                }
            }
        }).catch(error => {
            if(error.response.status == 422){
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                this.isLoading = false
            }
            else{
              swal('Klaida', error.response.data.message, 'error')
              this.isLoading = false
            }
        })
    },
    fixed: function(){
      swal({
        title: 'Patvirtinti įrankio sutvarkymą ir gražinimą į sandėlį',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          del: { text: 'Patvirtinti', value: true},
          cancel: {text: 'Atšaukti'}
        }
      }).then(value => {
        if(value){
          this.$http.post('/item/suspend/return/fixed', {id: this.itemData.ItemID})
          .then(response => {
              if(response.status == 200){
                  swal(response.data.message, response.data.success, "success")
                  this.loadItem()
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
        }})
    },
    editNote: function(){
      this.$http.post('/item/edit/note', {id: this.itemData.ItemID, note: this.note})
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
            this.$http.post('/item/delete', {id: this.itemData.ItemID}).then((response)=>{
                if(response.status == 200){
                    swal(response.data.message, response.data.success, "success").then(value => { this.$router.push({ path: '/group/'+this.itemData.ItemGroupID})})
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
    }
  },
  components: {
      vueImages,
      Loading,
      RenameItemModal,
      ChangeItemIdnumberModal,
      AddItemChipModal,
      ItemWarrantyFixModal,
      ItemUnwarrantedFixModal,
      ItemReturnConfirmation,
      ChangeItemWarrantyModal,
      RestoreItemModal,
      ConfirmReturnItemSuspentionModal,
      ChangeItemAcquiredModal
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
