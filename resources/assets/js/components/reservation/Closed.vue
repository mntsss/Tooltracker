<template>
    <v-container v-if="reservations">
      <v-layout row mx-0 wrap mx-0 align-center justify-center class="secondary v-toolbar">
        <v-flex shrink headline>Uždarytos (atiduotos) rezervacijos</v-flex>
      </v-layout>
      <v-layout align-center justify-center class="px-3 py-1" row wrap>
        <v-flex xs12 sm6 md4 d-flex>
          <v-select
            :items="users"
            v-model="selectedUser"
            label="Pagal vartotoją"
            prepend-icon="fa-user"
            item-text="Username"
            item-value="UserID"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm6 md4 d-flex>
          <v-menu
            :close-on-content-click="false"
            v-model="menu_from"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            min-width="290px"
          >
            <v-text-field
              slot="activator"
              v-model="date_from"
              label="Rodyti nuo..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker v-model="date_from" no-title scrollable locale="lt" first-day-of-week="1" @input="menu_from = false">
            </v-date-picker>
          </v-menu>
        </v-flex>
        <v-flex xs12 sm6 md4 d-flex>
          <v-menu
            :close-on-content-click="false"
            v-model="menu_til"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            min-width="290px"
          >
            <v-text-field
              slot="activator"
              v-model="date_til"
              label="Rodyti iki..."
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker v-model="date_til" no-title scrollable locale="lt" first-day-of-week="1" @input="menu_til = false">
            </v-date-picker>
          </v-menu>
        </v-flex>
      </v-layout>
      <v-layout align-center justify-center>
        <v-flex shrink>
          <v-btn color="primary" outline><v-icon class="px-2">fa-filter</v-icon>Filtruoti</v-btn>
        </v-flex>
      </v-layout>
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
export default{
  data(){
    return {
      users: [],
      selectedUser: null,
      date_from: new Date().toISOString().substr(0, 10),
      date_til: new Date().toISOString().substr(0, 10),
      menu_from: false,
      menu_til: false,
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
    this.loadUsers();
    this.loadReservations();
  },
  methods:{
    loadReservations: function(){
      this.$http.get('/reservation/closed/user/').then((response)=> {
        if(response.status == 200){
          this.$contentLoadingHide()
          this.reservations = response.data
        }
      }).catch(error => {
        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
      })
    },
    loadUsers: function(){
      this.$http.get('/user/list').then((response) => {
        this.users = response.data
      }).catch(error => {
        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
      })
    },
    show: function(name, param = {}){
        this.$modal.show(name, param)
    }
  },
  components: {
    vueImages
  }
}
</script>
<style>
  .signature{
      background-color: white !important;
  }
</style>
