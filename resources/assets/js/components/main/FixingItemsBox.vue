<template>
  <DataBox>
    <span slot="title">Taisomi įrankiai</span>
    <div slot="data-table">
      <div class="table-responsive data-box-table" v-if="items.length >0">
        <v-data-table :headers="suspentionsHeaders" :items="items" hide-actions class="elevation-3 border border-dark">
            <template slot="items" slot-scope="props">
              <tr class="cursor-pointer" @click="$router.push({ name: 'item', params: { itemID: props.item.item.id}})">
                <td class="h-auto py-2">
                  {{ props.item.item.group.ItemGroupName}}
                </td>
                <td class="h-auto py-2">
                  {{ props.item.item.name }}
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
</template>
<script>
import DataBox from './modules/data-box.vue';
import NoDataOverlay from './modules/no-data-overlay.vue';
export default{
  data(){
    return {
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
  created(){
    this.$store.dispatch('main/LOAD_ITEMS_FIXING');
  },
  computed: {
    items: function(){
      return this.$store.state.main.tools_suspended_fix;
    }
  },
  components: {
    DataBox,
    NoDataOverlay
  }
}
</script>
