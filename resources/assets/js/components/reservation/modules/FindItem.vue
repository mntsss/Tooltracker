<template>
    <v-container>
        <v-layout row mx-0 mx-0>
            <v-flex sm6 px-1>
              <v-text-field
                flat
                solo
                hide-details
                prepend-inner-icon="search"
                label="Įrankių paieška..."
                v-model = "searchQuery"
                class=""
              ></v-text-field>
              <template>
                <v-progress-linear :indeterminate="true" v-if="isSearchLoading"></v-progress-linear >
                <v-list>
                  <v-list-tile v-for="(item, i) in items" class="cursor-pointer" :key="i" @click="add(item)">
                    <v-list-tile-content >
                      <v-list-tile-title v-text="item.ItemName"></v-list-tile-title>
                      <v-list-tile-sub-title v-text="item.state"></v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-avatar class="headline font-weight-light">
                      <v-icon class="primary--text">fa-arrow-alt-circle-right</v-icon>
                    </v-list-tile-avatar>
                  </v-list-tile>
                </v-list>
              </template>
            </v-flex>
            <v-flex sm6 px-1>
              <v-card class="border border-light" scrollable>
                <v-card-title class="text-center secondary v-toolbar h5 mb-0">
                      Priskyrimui rezervuojami įrankiai
                </v-card-title>
                <v-card-text class="pa-0">
                  <!-- <v-list>
                    <v-list-tile v-for="(item, i) in reservationItems" :key="i">
                      <v-list-tile-content >
                        <v-list-tile-title v-text="item.item.ItemName"></v-list-tile-title>
                        <v-list-tile-sub-title v-text="'x'+item.quantity"></v-list-tile-sub-title>
                      </v-list-tile-content>
                      <v-list-tile-avatar class="headline font-weight-light cursor-pointer" @click="remove(item)">
                        <v-btn small icon><v-icon class="primary--text">fa-minus-circle</v-icon></v-btn>
                      </v-list-tile-avatar>
                    </v-list-tile>
                  </v-list> -->
                  <v-data-table :headers="headers" :items="reservationItems" hide-actions class="elevation-1">
                <template slot="items" slot-scope="props">
                <td>{{ props.item.item.item_group.ItemGroupName }}</td>
                  <td>{{ props.item.item.ItemName }}</td>
                  <td class="text-xs-center">{{ props.item.quantity }}</td>
                  <td class="justify-center layout px-0">
                      <v-btn @click="remove(props.item)"><v-icon >delete</v-icon></v-btn>
                  </td>
                </template>
                <template slot="no-data">
                    Laukiama rezervuojamų įrankių...
                </template>
              </v-data-table>
                </v-card-text>
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
        items: [],   //items array returned from search request
        isSearchLoading: false,
        searchQuery: null,
        headers: [
            {
              text: 'Grupė',
              align: 'left',
              sortable: false,
              value: 'item.item_group.ItemGroupName'
            },
            {
              text: 'Pavadinimas',
              align: 'left',
              sortable: false,
              value: 'item.ItemName'
            },
            { text: 'Kiekis (vnt.)', value: 'quantity', sortable:false },
            { text: '', value: 'value' }
          ],
      }
  },
  computed: {
    saveButtonDisabled: function(){
      if(this.reservationUser && this.reservedItems.length > 0)
        return false
      return true
    },
    reservationItems: function(){
        return this.$store.state.reservation.reservationItems
    }
  },
  methods:{
    add: function(item){
      if(item.state != "Sandėlyje"){
        if(item.state == 'Rezervuotas')
          return swal("Klaida!", 'Įrankis jau yra pridėtas aktyvioje rezervacijoje...', 'error')
        else if(item.state == 'Naudojamas')
          return swal("Klaida!", 'Įrankis yra naudojamas ir negali būti pridėtas į rezervaciją!', 'error')
        else if(item.state == 'Ištrintas')
          return swal("Klaida!", 'Įrankis yra ištrintas, todėl negali būti pridėtas į rezervaciją!', 'error')
        else
          return swal("Klaida!", 'Įrankis yra įšaldytas, todėl negali būti pridėtas į rezervaciją!', 'error')
      }
      else{
          for(var i = 0; i< this.reservationItems.length; i++)
            {
                if(this.reservationItems[i].item.ItemID == item.ItemID)
                    return swal("Klaida!", 'Įrankis jau rezervuotas!', 'error')
            }
            this.$emit('itemAdded')
            this.$store.commit('reservation/addItem', item)

        }
    },
    remove: function(item){
        this.$store.commit('reservation/removeItem', item)
    }
  },
  watch: {
    searchQuery (val) {
      if(this.searchQuery.length < 3)
        return

      this.isSearchLoading = true

      this.$http.post('/item/search', {query: this.searchQuery})
        .then(res => {
          this.items = res.data
        })
        .catch(err => {
          console.log(err)
        })
        .finally(() => (this.isSearchLoading = false))
    }
  }
}
</script>
