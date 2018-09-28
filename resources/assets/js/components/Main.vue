<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>

  <v-container>
    <v-layout row mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Įrankiai laukiantys patvirtinimo
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white position-relative">
                  <Loading :active.sync="suspentionLoading"
                  :can-cancel="false"
                  :is-full-page="false"></Loading>
                  <div class="table-responsive overview-box">
                    <v-data-table :headers="suspentionsHeaders" :items="unconfirmedReturnSuspentions" hide-actions class="elevation-3 border border-dark">
                        <template slot="items" slot-scope="props">
                          <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})" v-bind:class="{'text-danger': (days(props.item.created_at)>6)}">
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
                <v-data-table :headers="rentedHeaders" :items="longestRentedItems" hide-actions class="elevation-3 border border-dark">
                    <template slot="items" slot-scope="props">
                      <tr class="cursor-pointer" @click="$router.push({ name: 'rentedItem', params: { itemProp: props.item}})">
                        <td>
                          {{ props.item.RentedItemName}}
                        </td>
                        <td>
                          {{days(props.item.RentedItemDate)*props.item.RentedItemDailyPrice}} &euro;
                        </td>
                      </tr>
                    </template>
                    <template slot="no-data">
                        Nuomojamų įrankių nėra...
                    </template>
               </v-data-table>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
    <v-layout row mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Taisomi įrankiai
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white d-flex">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="primary" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Jūsų aktyvios rezervacijos
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="white">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="primary" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</div>
</template>
<script>
import CreateGroup from './modals/CreateGroup.vue';
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default {
  data(){
    return {
      itemGroups: [],
      isLoading: true,
      fullPage: false,
      suspentionLoading: true,
      longestRentedLoading: true,
      unconfirmedReturnSuspentions: [],
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
      this.isLoading = false
      this.getSuspentionsUnconfirmedReturn();
      this.getLongestRented();
  },
  computed: {
    username: function(){
      return this.$auth.user().UserName
    },
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
      },
      days: function(date){
          var dateRented = new Date(date)
          console.log(dateRented)
          var currentDate = new Date()
          if(dateRented > currentDate)
              return 0
          var timeDiff = Math.abs(currentDate.getTime() - dateRented.getTime());
          var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
          return diffDays
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
