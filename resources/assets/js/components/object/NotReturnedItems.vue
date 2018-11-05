<template>
  <v-container>
    <ItemReturnConfirmation v-on:reload="loadObject()"></ItemReturnConfirmation>
    <ItemReturnConsumable v-on:reload="loadObject()"></ItemReturnConsumable>
    <v-layout align-center justify-center>
      <v-flex>
        <v-card v-if="object">
          <v-layout class="mt-0 mx-0 mb-2 h5 secondary v-toolbar text-light text-center">
            <v-flex headline shrink justify-start align-content-center>
                <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
            </v-flex>
            <v-flex align-content-center>
                <div class="text-center h4 text-dark mt-2">Objekto "{{object.ObjectName}}" negrąžinti įrankiai</div>
            </v-flex>
          </v-layout>
          <v-layout  align-center justify-center v-if="object.item_withdrawals.length > 0">
            <v-flex shrink>
              <v-card tile>
                <div class="text-center p-5">
                  <v-icon class="display-4 primary--text" d-flex justify-center>fa-broadcast-tower</v-icon>
                </div>
                <v-progress-linear :indeterminate="true" color="red darken-2" background-color="primary" class="mb-5 mt-5"></v-progress-linear>
                <v-card-title class="headline">
                  Skanuokite objekto įrankius, kuriuos grąžinate į sandėlį
                </v-card-title>
              </v-card>
            </v-flex>
          </v-layout>
          <v-card-text v-if="object.item_withdrawals.length > 0">
            <v-data-table :headers="headers" :items="object.item_withdrawals" hide-actions class="elevation-3 border border-dark">
                <template slot="items" slot-scope="props">
                  <tr @click="itemDetails(props.item.item)" class="cursor-pointer">
                    <td>
                      {{ props.item.item.item_group.ItemGroupName}}
                    </td>
                    <td>
                      {{ props.item.item.ItemName }}
                    </td>
                    <td class="text-xs-center">
                      {{ props.item.ItemWithdrawalQuantity }}
                    </td>
                    <td class="px-0">
                      <v-btn outline color="primary" v-if="props.item.item.ItemConsumable"  @click.prevent="returnConsumable(props.item)">
                        <v-icon class="px-2">fa-sign-in-alt</v-icon>
                        Grąžinti į sandėlį
                      </v-btn>
                    </td>
                  </tr>
                </template>
              </v-data-table>
          </v-card-text>
          <div class="card-body mt-1 border border-dark" v-else>
            <div class="text-center h5 pa-2">
              Objekte įrankių nėra...
            </div>
          </div>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import swal from 'sweetalert'
import consumables from '../../mixins/consumables'
import ItemReturnConfirmation from '../modals/ItemReturnConfirmation.vue'
import ItemReturnConsumable from '../modals/withdrawal/ReturnConsumable.vue'
export default{
  mixins: [consumables],
  data(){
    return {
      object: null,
      headers: [
          {
            text: 'Grupė',
            align: 'left',
            value: 'item.group.ItemGroupName'
          },
          {
            text: 'Pavadinimas',
            align: 'left',
            sortable: false,
            value: 'item.ItemName'
          },
          {
            text: 'Kiekis (vnt.)',
            align: 'center',
            value: 'ItemWithdrawalQuantity'
          },
          {
            text: '',
            value: null,
            align: 'left'
          }
        ],
    }
  },
  props: {
    objectID: {
      type: Number,
      required: true
    }
  },
  created(){
    this.loadObject()
  },
  computed:{
    RfidCode: function(){
      return this.$store.state.recentCode;
    }
  },
  watch:
  {
    RfidCode(val){
      if(this.RfidCode != null && this.item == null)
      {
        this.getItemInfo(this.RfidCode)
        this.$store.commit('resetCode')
      }
    }
  },
  methods: {
    loadObject: function(){
      this.$http.get('/object/items/'+this.objectID)
      .then(response => {
          this.object = response.data
          this.object.item_withdrawals = this.addConsumables(this.object.item_withdrawals);
          this.$contentLoadingHide()
      })
      .catch(error =>{
            swal('Klaida', error.response.data.message, 'error')
      })
    },
    getItemInfo: function(code){
        this.$http.post('/item/findcode', {
            code: code
        }).then(response => {
            if(response.status == 200){
                if(response.data.state != "Naudojamas"){
                  if(item.state == 'Rezervuotas')
                    return swal("Klaida!", 'Įrankis jau yra pridėtas aktyvioje rezervacijoje...', 'error')
                  else if(item.state == 'Sandėlyje')
                    return swal("Klaida!", 'Įrankis yra sandėlyje (arba negrąžinamas)!', 'error')
                  else if(item.state == 'Ištrintas')
                    return swal("Klaida!", 'Įrankis yra ištrintas!', 'error')
                  else
                    return swal("Klaida!", 'Įrankis yra įšaldytas!', 'error')
                }
                else{
                    this.getWithdrawalInfo(response.data.ItemID)
                }
            }
        }).catch(err => {
            if(err.response.status == 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
    },
    getWithdrawalInfo: function(id){
        this.$http.get('/withdrawal/get/'+id).then(response => {
            if(response.status == 200){
                this.item = response.data
                this.$modal.show('item-return-confirm-modal', {item: this.item})
            }
        }).catch(err => {
            if(err.response.status == 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
    },
    itemDetails: function(item){
      if(!item.ItemConsumable)
        this.$router.push({ name: 'item', params: { itemID: item.ItemID}})

        // item/return/consumable
    },
    returnConsumable: function(withdrawal){
      this.$modal.show("item-return-consumable-modal", {withdrawal: withdrawal, objectID: this.object.ObjectID})
    }
  },
  components: {
    ItemReturnConfirmation,
    ItemReturnConsumable
  }
}
</script>
