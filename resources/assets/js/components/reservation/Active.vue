<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>
      <ConfirmWithCard></ConfirmWithCard>
      <ConfirmWithSign></ConfirmWithSign>
    <v-container v-if="reservations">
      <v-layout row wrap mx-0 align-center justify-center class="secondary v-toolbar">
        <v-flex shrink headline>Aktyvios rezervacijos</v-flex>
      </v-layout>
      <v-layout class="" row wrap align-center mx-0 mt-2 v-if="reservations.length > 0">
          <v-expansion-panel>
              <v-expansion-panel-content class="primary text-white v-toolbar mb-1" v-for="(reservation, i) in reservations" :key="i">
                  <div slot="header"  v-if="reservation.cobject">
                      {{reservation.cobject.ObjectName+' ('+reservation.recipient[0].Username+')'}}
                  </div>
                  <div slot="header"  v-else-if="reservation.cobject == null">
                      {{reservation.recipient[0].Username}}
                  </div>
                  <v-card>
                      <v-card-text>
                          <v-data-table :headers="headers" :items="reservation.items" hide-actions class="elevation-3 border border-dark">
                              <template slot="items" slot-scope="props">
                                <td>{{ props.item.item.ItemName }}</td>
                                <td class="text-xs-center">{{ props.item.ReservationItemQuantity }}</td>
                                <td class="justify-center layout px-0">
                                    <v-btn @click="deleteItem(props.item, i)"><v-icon>delete</v-icon></v-btn>
                                </td>
                              </template>
                              <template slot="no-data">
                                <v-alert :value="true" class="bg-warning" icon="warning">
                                  Rezervacija tuščia, arba įvyko klaida kraunant duomenis iš duombazės...
                                </v-alert>
                              </template>
                            </v-data-table>
                            <v-layout row wrap align-center pa-2 justify-end>
                                  <v-btn outline @click="show('confirm-reservation-with-card-modal', {id: reservation.ReservationID, user: reservation.recipient[0].Username})">
                                      <v-icon class="primary--text">fa-id-card</v-icon>
                                      <span class="mx-2">Patvirtinti kortele</span>
                                  </v-btn>
                                  <v-btn outline @click="show('confirm-reservation-with-sign-modal', {id: reservation.ReservationID, user: reservation.recipient[0].Username})">
                                        <v-icon class="primary--text">fa-signature</v-icon>
                                        <span class="mx-2">Patvirtinti parašu</span>
                                    </v-btn>
                                  <v-btn outline @click="deleteReservation(reservation)">
                                      <v-icon class="primary--text">fa-trash</v-icon>
                                      <span class="mx-2">Ištrinti</span>
                                  </v-btn>

                                </v-flex>
                            </v-layout>
                      </v-card-text>
                  </v-card>
              </v-expansion-panel-content>
          </v-expansion-panel>
      </v-layout>
      <div class="card-body mt-1 border border-dark" v-else-if="reservations.length == 0">
        <div class="text-center h5 pa-5">
          Aktyvių rezervacijų nėra...
        </div>
      </div>
    </v-container>
  </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
import ConfirmWithCard from '../modals/ConfirmReservationWithCard.vue'
import ConfirmWithSign from '../modals/ConfirmReservationWithSignature.vue'
export default{
  data(){
    return {
      isLoading: true,
      fullPage: false,
      reservations: null,
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
    this.loadReservations();
  },
  methods:{
    loadReservations: function(){
      this.$http.get('/reservation/list').then((response)=> {
        if(response.status == 200){
          this.isLoading = false
          this.reservations = response.data
        }
      }).catch(error => {
        swal('Klaida', error.response.data.message, 'error')
      })
    },
    deleteItem: function(item, i){
        swal({
          title: 'Ar tikrai norite iš rezervacijos pašalinti įrankį '+item.item.ItemName+'?',
          text: 'Iš rezervacijos panaikintas įrankis bus automatiškai perkeltas į sandėlį.',
          icon: 'warning',
          dangerMode: true,
          buttons: {
            del: { text: 'Pašalinti', value: true},
            cancel: {text: 'Atšaukti'}
          }
        }).then(value => {
            this.$http.post('/reservation/removeitem', {item: item}).then((response)=> {
                if(response.status == 200){
                    var index = this.reservations[i].items.indexOf(item)
                    this.reservations[i].items.splice(index, 1)
                }
            }).catch(error =>{
                if(error.response.status == 422)
                {
                    swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                }else {
                  swal('Klaida', error.response.data.message, 'error')
                }
            })
        })
    },
    deleteReservation: function(reservation){
        swal({
          title: 'Ar tikrai norite ištrinti šią rezervaciją?',
          text: 'Rezervacija bus panaikinta iš duomenų bazės ir visi rezervuoti įrankiai bus perkelti į sandėlį.',
          icon: 'warning',
          dangerMode: true,
          buttons: {
            del: { text: 'Trinti', value: true},
            cancel: {text: 'Atšaukti'}
          }
        }).then(value => {
            if(value){
                this.$http.get('/reservation/delete/'+reservation.ReservationID).then((response)=> {
                if(response.status == 200){
                    swal(response.data.message, response.data.success, "success")
                    this.loadReservations()
                }
            }).catch(error =>{
                if(error.response.status == 422)
                {
                    swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                }
                else{
                    swal("Klaida", error.response.data.message, "error");
                }
            })
            }
        })
    },
    show: function(name, param = {}){
        this.$modal.show(name, param)
    }
  },
  components: {
    Loading,
    ConfirmWithCard,
    ConfirmWithSign
  }
}
</script>
<style>
  .loading-parent{
    position: relative;
  }
</style>
