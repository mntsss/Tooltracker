<template>
  <v-container class="py-0 my-0" v-if='$user'>
    <v-layout row mx-0 mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <DataBox class="h-100">
          <span slot="title">
            Įrankiai laukiantys patvirtinimo
          </span>
          <div slot="data-table">
            <div class="table-responsive data-box-table" v-if="unconfirmedReturnSuspentions.length > 0">
              <v-data-table :headers="suspentionsHeaders" :items="unconfirmedReturnSuspentions" hide-actions class="elevation-3 table border border-dark">
                  <template slot="items" slot-scope="props">
                    <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})" v-bind:class="{'text-danger': (calcBusinessDays(props.item.created_at)>6)}">
                      <td class="h-auto py-2">
                        {{ props.item.item.item_group.ItemGroupName}}
                      </td>
                      <td class="h-auto py-2">
                        {{ props.item.item.ItemName }}
                      </td >
                      <td class="justify-center layout px-0 h-auto py-2">
                        {{ props.item.created_at}}
                      </td>
                    </tr>
                  </template>
             </v-data-table>
           </div>
           <NoDataOverlay v-else>
             <span slot="no-data-text">Nepatvirtintų grąžinimų nėra...</span>
           </NoDataOverlay>
          </div>
        </DataBox>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <v-container class="pa-0" style="margin-top: 0 !important">
          <v-layout fill-height>
            <v-flex>
              <v-card class="param-box" tile>
                <v-card-title class="text-center justify-center">
                  <v-icon class="display-3 text-success">fa-wrench</v-icon>
                </v-card-title>
                <v-card-text>
                  <div class="text-center">
                    Šį mėnesį taisymų:
                  </div>
                  <div class="text-center display-2 text-warning font-weight-bold">
                    {{statistics.monthlyFixes}}
                  </div>
                </v-card-text>
              </v-card>
            </v-flex>
            <v-flex>
              <v-card class="param-box" tile>
                <v-card-title class="text-center justify-center">
                  <v-icon class="display-3 text-warning">fa-euro-sign</v-icon>
                </v-card-title>
                <v-card-text>
                  <div class="text-center">
                    Šio mėnsio išlaidos įrankių nuomai
                  </div>
                  <div class="text-center display-2 text-warning font-weight-bold">
                    {{statistics.monthlyRentCost}}&euro;
                  </div>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
          <v-layout fill-height>
            <v-flex>
              <v-card class="param-box" tile>
                <v-card-title class="text-center justify-center">
                  <v-icon class="display-3 primary--text">fa-toolbox</v-icon>
                </v-card-title>
                <v-card-text>
                  <div class="text-center">
                    Įrankių skaičius sistemoje
                  </div>
                  <div class="text-center display-2 text-warning font-weight-bold">
                    {{statistics.totalItems}}
                  </div>
                </v-card-text>
              </v-card>
            </v-flex>
            <v-flex>
              <v-card class="param-box" tile>
                <v-card-title class="text-center justify-center">
                  <v-icon class="display-3 text-danger">fa-toolbox</v-icon>
                </v-card-title>
                <v-card-text>
                  <div class="text-center">
                    Naudojami įrankiai:
                  </div>
                  <div class="text-center display-2 text-warning font-weight-bold">
                    {{statistics.itemsInUse}}
                  </div>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>
    </v-layout>
    <v-layout mx-0 mx-0 justify-center fill-height>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <DataBox>
          <span slot="title">Taisomi įrankiai</span>
          <div slot="data-table">
            <div class="table-responsive data-box-table" v-if="fixingSuspentions.length >0">
              <v-data-table :headers="suspentionsHeaders" :items="fixingSuspentions" hide-actions class="elevation-3 border border-dark">
                  <template slot="items" slot-scope="props">
                    <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})">
                      <td class="h-auto py-2">
                        {{ props.item.item.item_group.ItemGroupName}}
                      </td>
                      <td class="h-auto py-2">
                        {{ props.item.item.ItemName }}
                      </td>
                      <td class="justify-center layout px-0 h-auto py-2">
                        {{ props.item.created_at}}
                      </td>
                    </tr>
                  </template>
             </v-data-table>
           </div>
           <NoDataOverlay v-else>
              <span slot="no-data-text">
                  Taisomų įrankių nėra...
              </span>
          </NoDataOverlay>
          </div>
        </DataBox>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <DataBox>
          <span slot="title">Ilgiausiai nuomojami įrankiai</span>
          <div slot="data-table">
            <v-data-table :headers="rentedHeaders" :items="longestRentedItems" hide-actions class="elevation-3 border border-dark" v-if="longestRentedItems.length>0">
                <template slot="items" slot-scope="props">
                  <tr class="cursor-pointer" @click="$router.push({ name: 'rentedItem', params: { itemProp: props.item}})">
                    <td class="h-auto py-2">
                      {{ props.item.RentedItemName}}
                    </td>
                    <td class="h-auto py-2">
                      {{calculatePrice(calcBusinessDays(props.item.RentedItemDate),props.item.RentedItemDailyPrice)}} &euro;
                    </td>
                  </tr>
                </template>
           </v-data-table>
           <NoDataOverlay v-else>
             <span slot="no-data-text">Nuomojamų įrankių nėra...</span>
           </NoDataOverlay>
          </div>
        </DataBox>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import CreateGroup from '../modals/CreateGroup.vue';
import swal from 'sweetalert';
import renttime from '../../mixins/renttime';

import DataBox from './modules/data-box.vue';
import NoDataOverlay from './modules/no-data-overlay.vue';

export default {
  mixins: [renttime],
  data(){
    return {
      itemGroups: [],
      fullPage: false,
      unconfirmedReturnSuspentions: [],
      fixingSuspentions: [],
      longestRentedItems: [],
      suspentionsHeaders: [
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
            text: 'Įšaldymo data',
            value: 'created_at',
            align: 'left'
          }
        ],
        rentedHeaders: [
            {
              text: 'Pavadinimas',
              align: 'left',
              value: 'item.RentedItemName',
              sortable: false
            },
            {
              text: 'Kaina',
              value: false,
              align: 'left',
              sortable: false
            }
          ],
        statistics: {
          monthlyFixes: 0,
          monthlyRentCost: 0,
          totalItems: 0,
          itemsInUse: 0
        }
    }
  },
  mounted(){
      this.$contentLoadingHide()
      this.getSuspentionsUnconfirmedReturn();
      this.getLongestRented();
      this.getFixingSuspentions();
      this.getMonthlyFixes();
      this.getMonthlyRentPrice();
      this.getTotalItems();
      this.getTotalItemsInUse();
  },
  computed: {
    RfidCode: function(){
        return this.$store.state.recentCode;
    }
  },
  watch:{
    RfidCode(val){
        if(this.RfidCode != null && this.item == null)
        {
          this.getItemInfo(this.RfidCode)
          this.$store.commit('resetCode')
        }
      }
    },
  methods: {
      getItemInfo: function(code){
          this.$http.post('/item/findcode', {
              code: code
          }).then(response => {
              if(response.status == 200){
                  this.$router.push({name: 'item', params: {itemProp: response.data}});
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
      getSuspentionsUnconfirmedReturn: function(){
          this.$http.get('/suspention/get/unconfirmedreturn')
          .then(response => {
              if(response.status == 200)
              {
                  this.unconfirmedReturnSuspentions = response.data
                  this.suspentionLoading = false
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
      getFixingSuspentions: function(){
          this.$http.get('/suspention/get/fixing')
          .then(response => {
              if(response.status == 200)
              {
                  this.fixingSuspentions = response.data
                  this.suspentionFixLoading = false
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
      getLongestRented: function(){
          this.$http.get('/rented/get/longest')
          .then(response => {
              if(response.status === 200)
              {
                  this.longestRentedItems = response.data
                  this.longestRentedLoading = false
              }
          }).catch(err => {
              if(err.response.status === 422)
              {
                  swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
              }
              else{
                  swal("Klaida", err.response.data.message, "error");
              }
          })
      },
      getMonthlyFixes: function(){
        this.$http.get('/statistics/get/monthlyFixes')
        .then(response => {
          if(response.status === 200)
          {
            this.statistics.monthlyFixes = response.data
          }
        }).catch(err => {
            if(err.response.status === 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
      },
      getMonthlyRentPrice: function(){
        this.$http.get('/statistics/get/rent')
        .then(response => {
          if(response.status === 200)
          {
            this.statistics.monthlyRentCost = response.data
          }
        }).catch(err => {
            if(err.response.status === 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
      },
      getTotalItems: function(){
        this.$http.get('/statistics/get/totalItems')
        .then(response => {
          if(response.status === 200)
          {
            this.statistics.totalItems = response.data
          }
        }).catch(err => {
            if(err.response.status === 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
      },
      getTotalItemsInUse: function(){
        this.$http.get('/statistics/get/totalItemsInUse')
        .then(response => {
          if(response.status === 200)
          {
            this.statistics.totalItemsInUse = response.data
          }
        }).catch(err => {
            if(err.response.status === 422)
            {
                swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", err.response.data.message, "error");
            }
        })
      }
  },
  components: {
    CreateGroup,
    DataBox,
    NoDataOverlay
  }
}
</script>
