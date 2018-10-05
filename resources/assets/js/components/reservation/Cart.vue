<template>
      <v-container>
          <ImageDialog :forceImage="true"></ImageDialog>
        <v-layout row mx-0 wrap mx-0 align-center justify-center class="primary text-light">
          <v-flex shrink headline>Įrankių rezervavimas darbų vygdytojams</v-flex>
        </v-layout>
        <v-layout>
            <v-select
                :items="objects"
                v-model="reservationObject"
                menu-props="auto"
                label="Pasirinkite rezervacijos objektą"
                hide-details
                item-text="ObjectName"
                item-value="ObjectID"
                return-object
                prepend-icon="fa-building"
                outline
                class="mb-4 mt-2"
            ></v-select>
        </v-layout>
        <v-layout>
            <v-select
                :items="reservationObject.foremen"
                v-model="reservationUser"
                menu-props="auto"
                label="Pasirinkite darbų vygdytoją"
                hide-details
                item-text="user.Username"
                item-value="user.UserID"
                prepend-icon="fa-user"
                outline
                class="mb-4 mt-2"
                v-if="reservationObject"
            >
            <span slot="no-data">Darbų vygdytojų nėra</span>
        </v-select>
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

      isSearchLoading: false,
      searchQuery: null,

      objects: [],
      users: [],
      items: [],

      reservationUser: null,
      reservationObject: null,

      //table setup
      headers: [
          {
            text: 'Įrankio (daikto) pavadinimas',
            align: 'left',
            sortable: false,
            value: 'item.ItemName'
          },
          { text: 'Kiekis (vnt.)', value: 'quantity' },
          { text: '', value: 'value' }
        ],
    }
  },
  created(){
    this.loadObjects()
  },
  mounted(){
    this.$contentLoadingHide()
  },
  beforeDestroy(){
    this.$store.commit('reservation/clearReservation')
    this.$store.commit('reservation/cancelItem')
  },
  computed: {
    RFIDCode: function(){
        return this.$store.state.recentCode
    },
    saveButtonDisabled: function(){
      if(this.reservationObject && this.reservationUser){
        if(this.reservationItems.length != 0)
            return false
      }
      return true
  },
  reservationItems: function(){
     return this.$store.state.reservation.reservationItems
  }
  },
  watch: {
    RFIDCode(oldRFIDCode, newRFIDCode){
        if(this.RFIDCode){
              this.$http.post('/item/findcode', {code: this.RFIDCode}).then((response) => {
                if(response.status == 200)
                    this.addItem(response.data)
              }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
              })

            this.$store.commit('resetCode')
        }
      }
  },
  methods: {
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
    loadObjects: function(){
      this.$http.get('/object/list').then((response) => {
        if(response.status == 200){
          this.objects = response.data
        }
      }).catch(error => {
        swal('Klaida!',error.response.data.message, 'error');
      })
    },

    save: function(){
      this.$http.post('/reservation/create', {
        objectID: this.reservationObject.ObjectID,
        userID: this.reservationUser,
        items: this.reservationItems
      }).then((response) => {
        swal(response.data.message, response.data.success, "success").then(value => {this.$router.push({name: 'reservations'})})
      }).catch(error => {
        if(error.response.status == 422)
        {
            swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
        }
        else{
            swal("Klaida", error.response.data.message, "error");
        }
      })
  }
  },
  components: {
     FindItem,
     ImageDialog
  }
}
</script>
<style>
  .overlay{
    z-index: 5;
    background-color: #FFF !important;
  }
  .loading-overlay{
    z-index: 10 !important;
  }
  .uploaded-image{
    max-width: 576px;
    padding: 10px;
    max-height: 50vh;
  }
</style>
