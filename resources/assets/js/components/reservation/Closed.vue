<template>
    <v-container v-if="reservations">
      <v-layout row mx-0 wrap mx-0 align-center justify-center class="secondary v-toolbar">
        <v-flex shrink headline>Uždarytos (atiduotos) rezervacijos</v-flex>
      </v-layout>
      <FilterBox :users = "users" @filter="loadReservations" @clear="loadReservations"></FilterBox>
      <v-layout class="" row mx-0 wrap align-center mx-0 mt-2 v-if="reservations.length > 0">
          <v-expansion-panel>
              <v-expansion-panel-content class="primary v-toolbar mb-1 text-white" v-for="(reservation, i) in reservations" :key="i">
                  <div slot="header" v-if="reservation.cobject">
                      {{reservation.cobject.ObjectName+' ('+reservation.recipient[0].Username+')'}}<span class="ml-2">{{reservation.updated_at}}</span>
                  </div>
                  <div slot="header" v-else-if="reservation.cobject == null">
                      {{reservation.recipient[0].Username}}<span class="ml-2">{{reservation.updated_at}}</span>
                  </div>
                  <v-card>
                      <v-card-text>
                          <v-data-table :headers="headers" :items="reservation.items" hide-actions class="elevation-3 border border-dark mb-3">
                              <template slot="items" slot-scope="props">
                                <td>{{ props.item.item.ItemName }}</td>
                                <td class="text-xs-center">{{ props.item.ReservationItemQuantity }}</td>
                                <td class="justify-center layout px-0">
                                </td>
                              </template>
                              <template slot="no-data">
                                <v-alert :value="true" class="bg-warning" icon="warning">
                                  Rezervacija tuščia, arba įvyko klaida kraunant duomenis iš duombazės...
                                </v-alert>
                              </template>
                            </v-data-table>
                            <v-layout row mx-0 wrap align-center>
                                <v-flex shrink pa-2 style="width: 40px !important">
                                    <v-icon headline class="primary--text">fa-calendar-plus</v-icon>
                                </v-flex>
                                <v-flex px-2 shrink>Rezervacija sukurta:</v-flex>
                                <v-flex px-2>{{reservation.created_at}}</v-flex>
                            </v-layout>
                            <v-layout row mx-0 wrap align-center>
                                <v-flex shrink pa-2 style="width: 40px !important">
                                    <v-icon headline class="primary--text">fa-calendar-check</v-icon>
                                </v-flex>
                                <v-flex px-2 shrink>Rezervacija atiduota:</v-flex>
                                <v-flex px-2>{{reservation.updated_at}}</v-flex>
                            </v-layout>
                            <v-layout row mx-0 wrap align-center v-if="reservation.ReservationConfirmCardNr">
                                <v-flex shrink pa-2 style="width: 40px !important">
                                    <v-icon headline class="primary--text">fa-id-card</v-icon>
                                </v-flex>
                                <v-flex px-2 shrink>Patvirtinta kortele</v-flex>
                                <v-flex px-2><v-icon headline class="text-success">fa-check</v-icon></v-flex>
                            </v-layout>
                            <v-layout row mx-0 wrap align-center v-else-if="reservation.ReservationConfirmSignature">
                                <v-flex shrink pa-2 style="width: 40px !important">
                                    <v-icon headline class="primary--text">fa-signature</v-icon>
                                </v-flex>
                                <v-flex px-2 shrink>Patvirtinta parašu</v-flex>
                                <v-flex px-2 shrink><v-icon headline class="text-success">fa-check</v-icon></v-flex>
                                <v-flex px-2 shrink>
                                    <vueImages :imgs="[{imageUrl: reservation.ReservationConfirmSignature, caption: reservation.recipient[0].Username+' - '+reservation.updated_at}]"
                                        :modalclose="true"
                                        :keyinput="true"
                                        :showcaption="true"
                                        :showclosebutton="true" class="signature">
                                    </vueImages>
                            </v-flex>
                            </v-layout>
                      </v-card-text>
                  </v-card>
              </v-expansion-panel-content>
          </v-expansion-panel>
      </v-layout>
      <div class="card-body mt-1 border border-dark" v-else-if="reservations.length == 0">
        <div class="text-center h5 pa-5">
          Uždarytų rezervacijų nėra...
        </div>
      </div>
    </v-container>
</template>
<script>
import swal from 'sweetalert'
import vueImages from 'vue-images'
import FilterBox from '../modules/FilterBox.vue'
export default{
  data(){
    return {
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
    this.loadUsers().then(() => this.loadReservations().then(() => this.$contentLoadingHide()));
  },
  computed: {
    users(){
      return this.$store.state.closedReservations.users;
    },
    reservations(){
      return this.$store.state.closedReservations.reservations;
    }
  },
  methods:{
    loadReservations: function(payload){
      if(payload){
        let {user, from, til} = payload;
        return this.$store.dispatch('closedReservations/LOAD_RESERVATIONS', {user, from, til});
      }
      else {
        return this.$store.dispatch('closedReservations/LOAD_RESERVATIONS', {user:'', from:'', til:''});
      }

    },
    loadUsers: function(){
      return this.$store.dispatch('closedReservations/LOAD_USERS');
    },
    show: function(name, param = {}){
        this.$modal.show(name, param)
    }
  },
  components: {
    vueImages,
    FilterBox
  }
}
</script>
<style>
  .signature{
      background-color: white !important;
  }
</style>
