<template>
<div class="loading-parent">
    <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
    <div class="container">
        <EditRentedItem></EditRentedItem>
      <AssignRentedItemModal></AssignRentedItemModal>
    <div class="card" v-if="itemData">
      <v-layout row wrap align-content-center class="card-header pb-0 pt-0 mx-0 theme--dark v-toolbar">
          <v-flex headline shrink justify-start align-content-center>
              <a @click="back()" class="headline"><span class="fa fa-arrow-left text-danger remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline">{{itemData.RentedItemName}}</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center>
                <a @click="$modal.show('edit-rented-item-modal', {item:itemData})" class="headline"><span class="fas fa-edit text-danger p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
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
                          <v-flex px-2 shrink v-if="!itemData.cobject">Sandėlyje</v-flex>
                          <v-flex px-2 shrink v-else-if="itemData.cobject">Naudojamas ({{itemData.cobject.ObjectName}})</v-flex>

                          <v-flex v-if="itemData.cobject"><v-btn outline class="mx-2" @click="unAssign()"><v-icon class="text-danger pr-3">fa-sign-in-alt</v-icon>Grąžinti į sandėlį</v-btn></v-flex>
                      </v-layout>
                      <v-layout row wrap align-center >
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-calendar-plus</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Nuomos pradžia:</v-flex>
                          <v-flex px-2>{{itemData.RentedItemDate}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.RentedItemDailyPrice">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-euro-sign</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Dieninis įkainis:</v-flex>
                          <v-flex px-2>{{itemData.RentedItemDailyPrice}}</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center v-if="itemData.RentedItemDailyPrice && itemData.RentedItemDate">
                          <v-flex shrink pa-2 style="width: 40px !important">
                              <v-icon headline class="text-danger">fa-money-bill-alt</v-icon>
                          </v-flex>
                          <v-flex px-2 shrink>Šiuo metu nuomos kaina:</v-flex>
                          <v-flex px-2>({{days(itemData.RentedItemDate)*itemData.RentedItemDailyPrice}} &euro;)</v-flex>
                      </v-layout>
                      <v-layout row wrap align-center>
                        <v-flex pa-2 xs10>
                          <v-textarea
                            name="note"
                            :disabled = 'readonly'
                            box
                            label="Nuomoto įrankio prierašas"
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
                            <v-btn outline v-if="!itemData.cobject" @click="show('assign-rented-item-modal', {itemID: itemData.RentedItemID})">
                                <v-icon class="text-danger">fa-wrench</v-icon>
                                <span class="mx-2">Priskirti objektui</span>
                            </v-btn>
                            <v-btn outline v-if="!itemData.cobject" @click="returnItem()">
                                <v-icon class="text-danger">fa-wrench</v-icon>
                                <span class="mx-2">Užbaigti nuomą</span>
                            </v-btn>
                          </v-flex>
                      </v-layout>
                  </v-card-text>
              </v-card>
            </v-layout>
        </v-container>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import AssignRentedItemModal from '../modals/rent/AssignRentedItem.vue'
import EditRentedItem from '../modals/rent/EditRentedItem.vue'
  export default {
    data(){
      return {
          itemData: '',
          note: '',
          readonly: true,
          isLoading: true,
          fullPage: false,
          dropdownMeniu: [
            {text: 'Priskirti čipą', click: () =>{this.show('add-item-chip-modal')}},
            {text: 'Pervadinti', click: ()=>{this.show('rename-item-modal')}},
            {text: 'Keisti identifikacinį numerį', click: ()=>{this.show('change-item-idnumber-modal')}},
            {text: 'Keisti garantinį laikotarpį', click: ()=>{this.$modal.show('change-item-warranty-modal', {itemID: this.itemData.ItemID, warranty: this.itemData.ItemWarranty})}},
            {text: 'Ištrinti', click: ()=>{this.deleteItem()}},
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
      this.itemData =  this.itemProp
      this.note = this.itemData.RentedItemNote
    }
  },
  mounted(){
    this.isLoading = false
  },
  methods: {
    show: function(name, params={}){
      this.$modal.show(name, params)
    },

    loadItem: function(){
            //this.isLoading = true
        return this.$http.get('/rented/get/'+this.itemData.RentedItemID).then((response)=>{
            if(response.status == 200){
                this.itemData = response.data
                this.note = this.itemData.note
            }
        }).catch(error => {
            if(error.response.status == 422){
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                this.isLoading = false
            }
        })
    },
    unAssign: function(){
      swal({
        title: 'Nuomotas įrankis grąžintas į sandėlį?',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          del: { text: 'Gražintas', value: true},
          cancel: {text: 'Atšaukti'}
        }
      }).then(value => {
        if(value){
          this.$http.post('/rented/assign', {id: this.itemData.RentedItemID})
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
      this.$http.post('/rented/edit', {id: this.itemData.RentedItemID, note: this.note})
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
    days: function(date){
        var dateRented = new Date(date)
        var currentDate = new Date();
        if(dateRented > currentDate)
            return 0
        var timeDiff = Math.abs(currentDate.getTime() - dateRented.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        return diffDays+1
    },
    returnItem: function(){
        swal({
          title: 'Įrankio nuomos užbaigimas',
          text: 'Ar tikrai norite užbaigti įrankio nuomą?',
          icon: 'warning',
          dangerMode: true,
          buttons: {
            del: { text: 'Taip', value: true},
            cancel: {text: 'Atšaukti'}
          }
        }).then(value => {
          if(value){
            this.$http.post('/rented/return', {id: this.itemData.RentedItemID}).then((response)=>{
                if(response.status == 200){
                    swal(response.data.message, response.data.success, "success")
                    this.back()
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
      Loading,
      AssignRentedItemModal,
      EditRentedItem
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
