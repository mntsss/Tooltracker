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

                  <v-data-table :headers="suspentionsHeaders" :items="unconfirmedReturnSuspentions" hide-actions class="elevation-3 border border-dark">
                      <template slot="items" slot-scope="props">
                        <tr class="cursor-pointer">
                          <td>
                            {{ props.item.item.item_group.ItemGroupName}}
                          </td>
                          <td>
                            {{ props.item.item.ItemName }}
                          </td>
                          <!-- <td class="text-xs-center">
                            {{ props.item. }}
                          </td> -->
                          <td class="justify-center layout px-0">
                            {{ props.item.created_at}}
                          </td>
                        </tr>
                      </template>
                      <template slot="no-data">
                        <v-alert :value="true" icon="success">
                          Nepatvirtintų grąžinimų nėra...
                        </v-alert>
                      </template>
                 </v-data-table>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="secondary h5">
                  Ilgiausiai nuomojami įrankiai
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
      unconfirmedReturnSuspentions: [],
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
        ]
    }
  },
  mounted(){
      this.isLoading = false
      this.getSuspentionsUnconfirmedReturn();
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
</style>
