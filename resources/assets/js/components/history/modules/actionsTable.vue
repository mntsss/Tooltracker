<template>
      <v-data-table prev-icon="fa-arrow-left"
              next-icon="fa-arrow-right"
              sort-icon="fa-angle-down"
              rows-per-page-text="Rodymi įrašai puslapyje: "
              :headers="headers" :items="actions"
              :pagination.sync="pagination"
              :rows-per-page-items='[25,50,100,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":25}]'
              :hide-actions="false" class="elevation-3 border border-dark">

              <template slot="items" slot-scope="props">
                  <tr class="cursor-pointer" @click="$emit('clicked', {item: props.item})">
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
import historyActions from '../../../mixins/historyActions'
export default{
  mixins: [historyActions],
  data(){
    return {
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
    }
  },
  computed: {
    actionDesc: function(){
      return historyActions;
    }
  },
  props:{
    actions: {
      required: true,
      type: Array
    }
  }
}
</script>
