<template>
  <v-container v-if='$user'>
    <v-layout row mx-0 mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Įrankiai laukiantys patvirtinimo
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white position-relative">
                  <Loading :active.sync="suspentionLoading"
                  :can-cancel="false"
                  :is-full-page="false"></Loading>
                  <div class="table-responsive overview-box" v-if="unconfirmedReturnSuspentions.length > 0">
                    <v-data-table :headers="suspentionsHeaders" :items="unconfirmedReturnSuspentions" hide-actions class="elevation-3 border border-dark">
                        <template slot="items" slot-scope="props">
                          <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})" v-bind:class="{'text-danger': (calcBusinessDays(props.item.created_at)>6)}">
                            <td>
                              {{ props.item.item.item_group.ItemGroupName}}
                            </td>
                            <td>
                              {{ props.item.item.ItemName }}
                            </td>
                            <td class="justify-center layout px-0">
                              {{ props.item.created_at}}
                            </td>
                          </tr>
                        </template>
                        <template slot="no-data">
                            Nepatvirtintų grąžinimų nėra...
                        </template>
                   </v-data-table>
                 </div>
                 <v-container class="hill-height" v-else>
                   <v-layout mx0 align-center justify-center>
                     <v-flex shrink><v-icon class="text-success display-3">fa-check</v-icon></v-flex>
                   </v-layout>
                   <v-layout align-center justify-center>
                     <v-flex shrink class="text-center h5">
                        Nepatvirtintų grąžinimų nėra...
                     </v-flex>
                   </v-layout>
                 </v-container>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Ilgiausiai nuomojami įrankiai
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white position-relative">
                <Loading :active.sync="longestRentedLoading"
                :can-cancel="false"
                :is-full-page="false"></Loading>
                <v-data-table :headers="rentedHeaders" :items="longestRentedItems" hide-actions class="elevation-3 border border-dark" v-if="longestRentedItems.length>0">
                    <template slot="items" slot-scope="props">
                      <tr class="cursor-pointer" @click="$router.push({ name: 'rentedItem', params: { itemProp: props.item}})">
                        <td>
                          {{ props.item.RentedItemName}}
                        </td>
                        <td>
                          {{calculatePrice(calcBusinessDays(props.item.RentedItemDate),props.item.RentedItemDailyPrice)}} &euro;
                        </td>
                      </tr>
                    </template>
                    <template slot="no-data">
                        Nuomojamų įrankių nėra...
                    </template>
               </v-data-table>
               <v-container class="hill-height" v-else>
                 <v-layout mx0 align-center justify-center>
                   <v-flex shrink><v-icon class="text-success display-3">fa-check</v-icon></v-flex>
                 </v-layout>
                 <v-layout align-center justify-center>
                   <v-flex shrink class="text-center h5">
                      Nuomojamų įrankių nėra...
                   </v-flex>
                 </v-layout>
               </v-container>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
    <v-layout row mx-0 mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Taisomi įrankiai
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white position-relative">
                  <Loading :active.sync="suspentionFixLoading"
                  :can-cancel="false"
                  :is-full-page="false"></Loading>
                  <div class="table-responsive overview-box" v-if="fixingSuspentions.length >0">
                    <v-data-table :headers="suspentionsHeaders" :items="fixingSuspentions" hide-actions class="elevation-3 border border-dark">
                        <template slot="items" slot-scope="props">
                          <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})">
                            <td>
                              {{ props.item.item.item_group.ItemGroupName}}
                            </td>
                            <td>
                              {{ props.item.item.ItemName }}
                            </td>
                            <td class="justify-center layout px-0">
                              {{ props.item.created_at}}
                            </td>
                          </tr>
                        </template>
                        <template slot="no-data">
                            Taisomų įrankių nėra...
                        </template>
                   </v-data-table>
                 </div>
                 <v-container class="hill-height" v-else>
                   <v-layout mx0 align-center justify-center>
                     <v-flex shrink><v-icon class="text-success display-3">fa-check</v-icon></v-flex>
                   </v-layout>
                   <v-layout align-center justify-center>
                     <v-flex shrink class="text-center h5">
                        Taisomų įrankių nėra...
                     </v-flex>
                   </v-layout>
                 </v-container>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  &nbsp;
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white">
                  <v-layout align-center mt-5 justify-center row mx-0 fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="primary" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import CreateGroup from './modals/CreateGroup.vue';
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
import renttime from '../mixins/renttime'
export default {
  mixins: [renttime],
  data(){
    return {
      itemGroups: [],
      fullPage: false,
      suspentionLoading: true,
      suspentionFixLoading: true,
      longestRentedLoading: true,
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
          ]
    }
  },
  mounted(){
      this.$contentLoadingHide()
      this.getSuspentionsUnconfirmedReturn();
      this.getLongestRented();
      this.getFixingSuspentions();
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
              if(response.status == 200)
              {
                  this.longestRentedItems = response.data
                  this.longestRentedLoading = false
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
      }
  },
  components: {
    CreateGroup,
    Loading
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    .overview-box{
      max-height: 35vh;
    }
</style>
