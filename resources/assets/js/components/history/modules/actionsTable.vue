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
                      <span v-if="props.item.storage">{{ props.item.storage.name}}</span>
                    </td>
                    <td>
                      {{ props.item.item.name}}
                    </td>
                    <td>
                      {{ props.item.previous_status}} -> {{props.item.current_status}}
                      <!--{{ actionDesc[props.item.previous_status][props.item.current_status]}}-->
                    </td>
                    <td>
                      {{ props.item.user.Username }}
                    </td>
                    <td class="text-xs-center">
                      {{ props.item.created_at }}
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
            text: "Sandėlis",
            align: 'left',
            sortable: false,
            value: 'storage.name'
          },
          {
            text: "Pavadinimas",
            align: 'left',
            sortable: false,
            value: 'item.name'
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
            value: 'user.Username'
          },
          {
            text: 'Data',
            value: 'created_at',
            align: 'left'
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
