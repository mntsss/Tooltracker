<template>

    <div class="container">
        <RenameItemModal></RenameItemModal>
        <ChangeItemIdnumberModal></ChangeItemIdnumberModal>
        <AddItemChipModal></AddItemChipModal>
        <ItemWarrantyFixModal></ItemWarrantyFixModal>
        <ItemUnwarrantedFixModal></ItemUnwarrantedFixModal>
        <ChangeItemWarrantyModal></ChangeItemWarrantyModal>
        <RestoreItemModal></RestoreItemModal>
        <ItemReturnConfirmation v-on:reload="loadItem"></ItemReturnConfirmation>
        <ConfirmReturnItemSuspentionModal></ConfirmReturnItemSuspentionModal>
        <ChangeItemAcquiredModal></ChangeItemAcquiredModal>
        <QRreader></QRreader>
    <div class="card" v-if="item">
      <v-layout row mx-0 wrap align-content-center class="card-header pb-0 pt-0 mx-0 secondary">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline">{{item.name}}</div>
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
                      <v-layout row mx-0 align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-map-marker</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Būsena:</v-flex>
                          <v-flex px-2 shrink>{{item.status}}</v-flex>
                          <v-flex v-if="itemStatus == 'Naudojamas' && item.last_withdrawal.object">({{item.last_withdrawal.object.ObjectName}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Naudojamas' && item.last_withdrawal.user">({{item.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Laukia patvirtinimo' && item.last_withdrawal.user">({{item.last_withdrawal.user.Username}})</v-flex>
                          <v-flex v-else-if="itemStatus == 'Ištrintas'"><v-btn icon class="text-warning px-3" @click="show('restore-item-modal')"><v-icon>fa-undo</v-icon></v-btn></v-flex>
                          <v-flex v-if="itemStatus == 'Taisomas' || itemStatus == 'Garantinis taisymas'"><v-btn outline class="mx-2" @click="fixed()"><v-icon class="primary--text pr-3">fa-check</v-icon>Sutaisyta</v-btn></v-flex>
                          <v-flex v-else-if="itemStatus == 'Naudojamas'"><v-btn outline class="mx-2" @click="returnItem()"><v-icon class="primary--text pr-3">fa-sign-in-alt</v-icon>Grąžinti į sandėlį</v-btn></v-flex>
                          <v-flex v-else-if="itemStatus == 'Laukia patvirtinimo'"><v-btn outline class="mx-2" @click="$modal.show('confirm-return-item-suspention-modal', {itemID: item.ItemID})"><v-icon class="primary--text pr-3">fa-check</v-icon>Patvirtinti grąžinimą</v-btn></v-flex>
                      </v-layout>
                      <v-layout row mx-0 align-center v-if="item.last_suspention">
                          <v-flex pa-2 xs10 v-if="!item.last_suspention.SuspentionReturned && item.last_suspention.SuspentionNote">
                            <v-textarea
                              name="note"
                              :disabled = 'true'
                              box
                              label="Įšaldymo komentaras"
                              auto-grow
                              v-model= "item.last_suspention.SuspentionNote"
                            ></v-textarea>
                          </v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center v-if="item.identification">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-fingerprint</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Identifikacinis numeris:</v-flex>
                          <v-flex px-2>{{item.identification}}</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center v-if="item.acquired_from">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-shopping-bag</v-icon>
                          </v-flex>
                          <v-flex shrink px-2>Įsigyta iš:</v-flex>
                          <v-flex px-2>{{item.acquired_from}}</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-plus</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Pridėjimo data:</v-flex>
                          <v-flex px-2>{{item.created_at}}</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center v-if="item.ItemPurchase">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-alt</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Įsigijimo data:</v-flex>
                          <v-flex px-2>{{item.purchase_date}}</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center v-if="item.warranty_date">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-calendar-check</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Garantinis iki:</v-flex>
                          <v-flex px-2>{{item.warranty_date}}</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center v-if="item.consumable">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="primary--text">fa-check</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Suvartojama</v-flex>
                      </v-layout>
                      <v-layout row mx-0 wrap align-center>
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
                      <v-layout row mx-0 wrap align-center pa-2 justify-end>
                          <v-flex shrink justify-end>
                            <v-btn outline v-if="!item.ItemDeleted && warrantyFix && itemStatus == 'Sandėlyje' && !item.ItemConsumable" @click="show('item-warranty-fix-modal')">
                                <v-icon class="primary--text">fa-wrench</v-icon>
                                <span class="mx-2">Garantinis taisymas</span>
                            </v-btn>
                            <v-btn outline v-if="!item.ItemDeleted && itemStatus == 'Sandėlyje' && !item.ItemConsumable" @click="show('item-unwarranted-fix-modal')">
                                <v-icon class="primary--text">fa-wrench</v-icon>
                                <span class="mx-2">Taisymas</span>
                            </v-btn>
                              <v-btn outline @click="$router.push({ name: 'itemHistory', params: { id: item.ItemID, type: 'item'}})">
                                  <v-icon class="primary--text">fa-history</v-icon>
                                  <span class="mx-2">Istorija</span>
                              </v-btn>
                          </v-flex>
                      </v-layout>
                  </v-card-text>
              </v-card>
            </v-layout>
        <div v-if="item.images">
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
        </div>
        </v-container>
      </div>
    </div>
  </div>
</template>
<script>
import vueImages from 'vue-images'
import swal from 'sweetalert'
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
import QRreader from '../modals/QRreader.vue';
  export default {
    data(){
      return {
          images: [],
          note: '',
          readonly: true,

          dropdownMeniu: [
            {text: 'Priskirti kodą', click: () =>{this.$modal.show('qr-reader-modal')}},
            {text: 'Pervadinti', click: ()=>{this.show('rename-item-modal', {name: this.item.ItemName})}},
            {text: 'Keisti identifikacinį numerį', click: ()=>{this.show('change-item-idnumber-modal', {ident: this.item.ItemIdNumber})}},
            {text: 'Keisti įsigijimo vietą', click: ()=>{this.show('change-item-acquired-modal', {acquired: this.item.ItemAcquiredFrom})}},
            {text: 'Keisti garantinį laikotarpį', click: ()=>{this.show('change-item-warranty-modal', {warranty: this.item.ItemWarranty})}},
            {text: 'Ištrinti', click: ()=>{this.deleteItem()}},
          ]
      }
  },
  props: {
      itemID:{
          required: true,
          type: Number
      }
  },
  created(){
    if(this.item === null){
        this.loadItem()
    }
    else if(this.item.id != this.itemID){
      this.loadItem();

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
    this.$contentLoadingHide()
  },
  computed: {
    item: function() {
      return this.$store.state.item.item;
    },
    warrantyFix: function(){
      if(!this.item.warranty_date)
        return false
      else{
        var now = new Date()
        var formatedWarranty = new Date(this.item.warranty_date)
        if(now.getTime() < formatedWarranty.getTime())
          return true
        else
          return false
      }
    }
  },
  methods: {
    show: function(name, params = {}){
      params.itemID = this.item.ItemID
      this.$modal.show(name, params)
    },
    returnItem: function(){
        const item = this.item
        item.last_withdrawal.image = item.images[0]
        this.$modal.show('item-return-confirm-modal', {item: item})
    },
    loadItem: function(){
        this.$store.dispatch('item/LOAD_ITEM', {id: this.itemID});
        this.$contentLoadingHide();
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
          this.$http.post('/item/suspend/return/fixed', {id: this.item.ItemID})
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
      this.$http.post('/item/edit', {id: this.item.ItemID, note: this.note})
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
            this.$http.post('/item/delete', {id: this.item.id}).then((response)=>{
                if(response.status == 200){
                    swal(response.data.message, response.data.success, "success").then(value => { this.$router.push({ path: '/group/'+this.item.ItemGroupID})})
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
      RenameItemModal,
      ChangeItemIdnumberModal,
      AddItemChipModal,
      ItemWarrantyFixModal,
      ItemUnwarrantedFixModal,
      ItemReturnConfirmation,
      ChangeItemWarrantyModal,
      RestoreItemModal,
      ConfirmReturnItemSuspentionModal,
      ChangeItemAcquiredModal,
      QRreader
  }
}
</script>
