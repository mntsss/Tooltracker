<template>
  <div class="container" style="min-height: 70vh !important">
    <div class="card" v-if="user">
      <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary v-toolbar" >
          <v-flex headline shrink justify-start align-content-center>
              <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
          </v-flex>
          <v-flex>
              <div class="text-center headline" v-if="user">Vartotojo {{user.Username}} įrankiai</div>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="user.withdrawals.length > 0">
          <v-data-table :headers="headers" :items="user.withdrawals" hide-actions class="elevation-3 border border-dark">
              <template slot="items" slot-scope="props">
                <tr @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})" class="cursor-pointer">
                  <td>
                    {{ props.item.item.item_group.ItemGroupName}}
                  </td>
                  <td>
                    {{ props.item.item.ItemName }}
                  </td>
                  <td class="text-xs-center">
                    {{ props.item.ItemWithdrawalQuantity }}
                  </td>
                  <td class="justify-center layout px-0">
                    {{ props.item.created_at}}
                  </td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert :value="true" class="bg-warning" icon="warning">
                  Vartotojas neturi įrankių
                </v-alert>
              </template>
            </v-data-table>
      </div>
      <div class="card-body mt-1 border border-dark" v-else-if="user.withdrawals.length == 0">
        <div class="text-center h5 pa-5">
          Vartotojas įrankių neturi...
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import swal from 'sweetalert'

  export default {
    data(){
      return {
        user: null,
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
              text: 'Išdavimo data',
              value: 'created_at',
              align: 'left'
            }
          ]
      }
    },
    props: {
        userID: {
            required: true,
            type: Number
        }
    },
    created(){
        this.loadUser()
    },
    methods: {
        loadUser: function(){
            return this.$http.get('/user/withdrawals/'+this.userID).then((response)=>{
                if(response.status == 200){
                    this.user = response.data
                    this.$contentLoadingHide()
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        }
    },
    components: {
    }
}
</script>
