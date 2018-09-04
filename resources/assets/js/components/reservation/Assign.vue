<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>
    <v-container>
      <v-layout row wrap mx-0 align-center justify-center color="primary">
        <v-flex shrink headline>Įrankių priskyrimas vartotojui</v-flex>
      </v-layout>
      <v-layout>
        <v-select
          :items="users"
          :persistent-hint = 'true'
          v-model="reservationUser"
          menu-props="auto"
          label="Pasirinkite vartotoją, kuriam priskirsite įrankius"
          hide-details
          item-text="Username"
          return-object
          prepend-icon="fa-user"
          outline
          class="mb-4 mt-2"
        ></v-select>
      </v-layout>
      <v-layout row mx-0 v-if="reservationUser">
        <v-flex sm6 px-1>
          <v-text-field
            flat
            solo-inverted
            hide-details
            prepend-inner-icon="search"
            label="Įrankių paieška..."
            v-model = "searchQuery"
            class=""
          ></v-text-field>
          <template v-if="items || isSearchLoading" >
            <v-progress-linear :indeterminate="true" v-if="isSearchLoading"></v-progress-linear >
            <v-list>
              <v-list-tile v-for="(item, i) in items" class="cursor-pointer" :key="i" @click="add(item)">
                <v-list-tile-content >
                  <v-list-tile-title v-text="item.item.ItemName"></v-list-tile-title>
                  <v-list-tile-sub-title v-text="item.state"></v-list-tile-sub-title>
                </v-list-tile-content>
                <v-list-tile-avatar class="headline font-weight-light">
                  <v-icon class="text-danger">fa-arrow-alt-circle-right</v-icon>
                </v-list-tile-avatar>
              </v-list-tile>
            </v-list>
          </template>
        </v-flex>
        <v-flex sm6 px-1>
          <v-card class="border border-light" scrollable>
            <v-card-title class="text-center theme--dark v-toolbar h5 mb-0">
                  Priskyrimui rezervuojami įrankiai
            </v-card-title>
            <v-card-text class="pa-0">
              <v-list>
                <v-list-tile v-for="(item, i) in reservedItems" :key="i">
                  <v-list-tile-content >
                    <v-list-tile-title v-text="item.item.ItemName"></v-list-tile-title>
                    <v-list-tile-sub-title v-text="item.state"></v-list-tile-sub-title>
                  </v-list-tile-content>
                  <v-list-tile-avatar class="headline font-weight-light cursor-pointer" @click="remove(item)">
                    <v-btn small icon><v-icon class="text-danger">fa-minus-circle</v-icon></v-btn>
                  </v-list-tile-avatar>
                </v-list-tile>
              </v-list>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
      <v-layout row wrap mx-0 pa-3 justify-center align-center>
        <v-flex shrink><v-btn outline @click="save" :disabled="saveButtonDisabled"><v-icon class="text-danger headline mx-2">fa-save</v-icon>Išsaugoti rezervaciją</v-btn></v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'

export default{
  data(){
      return {
        isLoading: true,
        fullPage: false,

        users: [],
        items: [],

        reservationUser: null,
        reservedItems: [],
        isSearchLoading: false,
        searchQuery: null
      }
  },
  created(){
    this.loadUsers()
  },
  computed: {
    saveButtonDisabled: function(){
      if(this.reservationUser && this.reservedItems.length > 0)
        return false
      return true
    }
  },
  methods:{
    loadUsers: function(){
      this.$http.get('/user/list').then(response => {
        if(response.status == 200){
          this.users = response.data
          this.isLoading = false
        }
      }).catch(error => {
        swal("Klaida", error.response.data.message, "error");
      })
    },
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
        this.reservedItems.push(item)
      }
    },
    remove: function(item){
        var index = this.reservedItems.indexOf(item);
        this.reservedItems.splice(index, 1)
    },
    save: function(){
        this.$http.post('/reservation/assign', {
            items: this.reservedItems,
            user: this.reservationUser
        }).then(response => {
            if(response.status == 200){
                swal(response.data.message, response.data.success, 'success').then(value => { this.$router.push({ name: 'reservations'})})
            }
        }).catch(err => {
            if(err.response.status == 422){
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }else
                swal('Klaida!', err.response.data.message, 'error')
        })
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
  },
  components: {
    Loading,
  }
}
</script>
<style>
  .loading-parent{
    position: relative;
  }
</style>
