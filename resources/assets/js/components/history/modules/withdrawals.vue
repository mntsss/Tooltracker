<template>
      <v-data-table prev-icon="fa-arrow-left"
              next-icon="fa-arrow-right"
              sort-icon="fa-angle-down"
              rows-per-page-text="Rodymi įrašai puslapyje: "
              :headers="headers" :items="itemsHistory"
              :pagination.sync="pagination"
              :rows-per-page-items='[25,50,100,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":25}]'
              :hide-actions="false" class="elevation-3 border border-dark">

              <template slot="items" slot-scope="props">
                  <tr class="cursor-pointer">
                    <td>
                      {{ props.item.GroupName}}
                    </td>
                    <td>
                      {{ props.item.ItemName}}
                    </td>
                    <td>
                      {{ actionDesc[props.item.Action][props.item.Type][props.item.Subtype]}}
                    </td>
                    <td>
                      {{ props.item.Username }}
                    </td>
                    <td class="text-xs-center">
                      {{ props.item.Date }}
                    </td>
                    <td class="justify-center layout px-0">
                      {{ props.item.Quantity}}
                    </td>
                  </tr>
                </template>
                <template slot="no-data">
                  <v-alert :value="true" class="bg-warning" icon="warning">
                    Įrankių istorija tuščia...
                  </v-alert>
                </template>
          </v-data-table>
</template>
<script>
import swal from 'sweetalert'
export default{
  data(){
    return {

      itemsHistory: [],
      pagination: {
          sortBy: 'Date',
          descending: true,
          rowsPerPage: 25,
      },
      headers: [
          {
            text: "Grupė",
            align: 'left',
            sortable: false,
            value: 'item.itemGroup.ItemGroupName'
          },
          {
            text: "Pavadinimas",
            align: 'left',
            sortable: false,
            value: 'ItemName'
          },
          {
            text: 'Veiksmas',
            align: 'left',
            value: false,
            sortable: false
          },
          {
            text: 'Vartotojas',
            align: 'left',
            sortable: false,
            value: 'Username'
          },
          {
            text: 'Data',
            value: 'Date',
            align: 'left'
          },
          {
            text: 'Kiekis',
            value: 'Quantity',
            sortable: false,
            align: 'center'
          }
        ],
      actionDesc: {
          suspention: {
              in: {
                  unconfirmed_return: "Grąžinimas patvirtintas",
                  warranty_fix: "Grįžo iš garantinio",
                  unwarranted_fix: "Sutaisyta"
              },
              out: {
                  unconfirmed_return: "Įšaldyta: Nepatvirtintas grąžinimas",
                  warranty_fix: "Įšaldyta: Garantinis taisymas",
                  unwarranted_fix: "Įšaldyta: Taisymas"
              }
          },
          withdrawal: {
              in: {
                  null: "Įrankis grąžintas"
              },
              out: {
                  null: "Įrankis priskirtas vartotojui"
              }
          },
          reservation: {
              in: {
                  null: "Įrankio rezervacija patvirtinta"
              },
              out: {
                  null: "Įrankis rezervuotas"
              }
          }
      }

    }
  },
  props:{
    url: {
      required: false,
      type: String
    }
  },
  created(){
    this.loadItemHistory()
  },
  computed: {
    request_url: function(){
      if(this.url){
        return this.url
      }
      else{
        return "history/item/all"
      }
    }
  },
  methods:{
    loadItemHistory: function(){
      this.$http.get(this.request_url)
      .then(response => {
        this.itemsHistory = response.data
        this.$contentLoadingHide()
      }).catch(err => {
        swal("Klaida", err.response.data.message, 'warning')
      })
    }
  }
}
</script>
