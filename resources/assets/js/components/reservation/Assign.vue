<template>
    <v-container>
        <ImageDialog :forceImage="false"></ImageDialog>
      <v-layout row mx-0 wrap align-center justify-center class="primary text-light">
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
      <FindItem v-on:itemAdded="itemAdded()"></FindItem>
      <v-layout row mx-0 wrap mx-0 pa-3 justify-center align-center>
        <v-flex shrink><v-btn outline @click="save" :disabled="saveButtonDisabled"><v-icon class="primary--text headline mx-2">fa-save</v-icon>Išsaugoti rezervaciją</v-btn></v-flex>
      </v-layout>
    </v-container>
</template>
<script>
import swal from 'sweetalert'
import FindItem from './modules/FindItem.vue'
import ImageDialog from './modules/ImageDialog.vue'
export default{
  data(){
      return {
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
  beforeDestroy(){
    this.$store.commit('reservation/clearReservation')
    this.$store.commit('reservation/cancelItem')
  },
  computed: {
    saveButtonDisabled: function(){
      if(this.reservationUser && this.reservationItems.length > 0)
        return false
      return true
    },
    RFIDCode: function(){
         return this.$store.state.recentCode
    },
    reservationItems: function(){
        return this.$store.state.reservation.reservationItems
    }
  },
  watch:{
      RFIDCode(oldRFIDCode, newRFIDCode){
          if(this.RFIDCode){
                this.$http.post('/item/findcode', {code: this.RFIDCode}).then((response) => {
                  if(response.status == 200)
                      this.addItem(response.data)
                }).catch(error => {
                    console.log(error)
                  swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                })
              }
              this.$store.commit('resetCode')
     }
  },
  methods:{
    loadUsers: function(){
      this.$http.get('/user/list').then(response => {
        if(response.status == 200){
          this.users = response.data
          this.$contentLoadingHide()
        }
      }).catch(error => {
        swal("Klaida", error.response.data.message, "error");
      })
    },
    addItem: function(item){
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
            this.itemAdded()
            this.$store.commit('reservation/addItem', item)
        }
    },
    itemAdded: function(){
        this.$modal.show('waiting-image-modal')
    },
    save: function(){
        this.$http.post('/reservation/assign', {
            items: this.reservationItems,
            userID: this.reservationUser.UserID
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
  components: {
      FindItem,
      ImageDialog
  }
}
</script>
