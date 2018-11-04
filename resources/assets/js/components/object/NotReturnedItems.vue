<template>
  <v-container>
    <v-layout align-center justify-center>
      <v-flex>
        <v-card v-if="object">
          <v-card-title class="mt-0 mx-0 mb-2 headline primary v-toolbar text-light text-center">
            Objekto "{{object.ObjectName}}" negrąžinti įrankiai
          </v-card-title>
          <v-card-text v-if="object.item_withdrawals.length > 0">
            <v-data-table :headers="headers" :items="object.item_withdrawals" hide-actions class="elevation-3 border border-dark">
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
                    <td class="px-0">
                      {{ props.item.user.Username}}
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
export default{
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
            text: 'Vartotojas',
            value: 'item.user.Username',
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
  watch:{

  },
  methods: {
    loadObject: function(){
      this.$http.get('/object/items/'+this.objectID)
      .then(response => {
          this.object = response.data
          this.$contentLoadingHide()
      })
      .catch(error =>{
          if(error.response.status == 422)
          {
              swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
          }
          else {
            swal('Klaida', error.response.data.message, 'error')
          }
      })
    }
  }
}
</script>
