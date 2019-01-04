<template>
  <DataBox>
    <span slot="title">Ilgiausiai nuomojami įrankiai</span>
    <div slot="data-table">
      <v-data-table :headers="rentedHeaders" :items="items" hide-actions class="elevation-3 border border-dark" v-if="items.length>0">
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
</template>
<script>
import DataBox from './modules/data-box.vue';
import NoDataOverlay from './modules/no-data-overlay.vue';
import renttime from '../../mixins/renttime';
export default{
  mixins: [renttime],
  data(){
    return {
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
  created(){
    this.$store.dispatch('main/LOAD_LONGEST_RENTED_ITEMS');
  },
  computed: {
    items: function(){
      return this.$store.state.main.longest_rented_items;
    }
  },
  components: {
    DataBox,
    NoDataOverlay,
    renttime
  }
}
</script>
