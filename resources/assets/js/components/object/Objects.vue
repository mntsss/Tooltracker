<template>
    <v-container>
        <AddObject></AddObject>
      <AssingForeman></AssingForeman>
        <v-layout row mx-0 v-if="objects">
                <v-layout row mx-0 mb-3 class="secondary v-toolbar">
                    <v-flex headline align-center>
                        <div class="text-center mb-0">
                            Objektai
                        </div>
                    </v-flex>
                    <v-flex shrink v-if="$user.UserRole == 'Administratorius'">
                        <a @click="show('add-object-modal')" class="headline"><span class="fas fa-plus primary--text p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
                    </v-flex>
                </v-layout>
                <v-card-text v-if="objects.length > 0">
                    <v-layout row mx-0 mx-0>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(object, i) in objects" :key="i">
                                <div slot="header">
                                    <span class="h5">{{object.ObjectName}}</span>
                                </div>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-calendar-plus</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Objektas sukurtas:</v-flex>
                                            <v-flex px-2>{{object.created_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center justify-center>
                                            <v-container>
                                                <v-card-title class="primary v-toolbar text-white mx-auto pa-1 v-layout align-center justify-center">
                                                  <v-flex px-2 class="text-center"><v-icon headline class="white--text px-2">fa-user-tie</v-icon>Darbų vygdytojai</v-flex>
                                                  <v-flex shrink v-if="$user.UserRole == 'Administratorius'">
                                                      <v-btn icon @click="show('assign-foreman-modal', {objectID: object.ObjectID})" class="my-0">
                                                        <v-icon class="white--text">fa-plus</v-icon>
                                                      </v-btn>
                                                  </v-flex>
                                                </v-card-title>

                                                <v-card-text>
                                                    <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer h6 v-layout align-center mb-0" :to="{ name: 'userWithdrawals', params: { userID: foreman.UserID}}" v-for="(foreman, i) in object.foremen" :key="i">
                                                      <div class="col-5 h6">
                                                        {{foreman.user.Username}}
                                                      </div>
                                                      <div class="col-5 text-center h6">
                                                        {{foreman.created_at}}
                                                      </div>
                                                      <div class="col text-center">
                                                        <v-btn icon @click.prevent="removeForeman(foreman.user, object.ObjectID)" class="ma-0">
                                                          <v-icon class="primary--text">fa-minus</v-icon>
                                                        </v-btn>
                                                      </div>
                                                    </router-link>
                                                </v-card-text>
                                            </v-container>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center justify-center v-if="object.rented.length > 0">
                                            <v-container>
                                                <v-card-title class="primary v-toolbar text-white mx-auto pa-1">
                                                    <h5>Išnuomoti įrankiai / nuomos pradžia</h5>
                                                </v-card-title>
                                                <v-card-text>
                                                    <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer h6" :to="{ name: 'rentedItem', params: { itemProp: item}}" v-for="(item, i) in object.rented" :key="i">
                                                      <div class="col-6 h6">
                                                        {{item.RentedItemName}}<span class="ml-2" v-if="item.RentedItemDate">({{calcBusinessDays(item.RentedItemDate)*item.RentedItemDailyPrice}} &euro;)</span>
                                                      </div>
                                                      <div class="col text-center h6">
                                                        {{item.RentedItemDate}}
                                                      </div>
                                                    </router-link>
                                                </v-card-text>
                                            </v-container>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center justify-center>
                                            <v-container>
                                                <v-card-title class="primary v-toolbar text-white mx-auto pa-1">
                                                    <h5>Naudojami įrankiai / išdavimo data</h5>
                                                </v-card-title>
                                                <v-card-text v-if="object.item_withdrawals.length > 0">
                                                  <v-data-table :headers="headers" :items="object.item_withdrawals" hide-actions class="elevation-3 border border-dark">
                                                      <template slot="items" slot-scope="props">
                                                        <tr @click="$router.push({ name: 'item', params: { itemID: props.item.item.ItemID}})" class="cursor-pointer">
                                                          <td>
                                                            {{ props.item.item.item_group.ItemGroupName}}
                                                          </td>
                                                          <td>
                                                            {{ props.item.item.ItemName }}
                                                          </td>
                                                          <td class="text-xs-center">
                                                            {{ props.item.ItemWithdrawalQuantity }}
                                                          </td>
                                                          <td class="justify-center layout px-0">
                                                            {{ props.item.created_at}}
                                                          </td>
                                                        </tr>
                                                      </template>
                                                      <template slot="no-data">
                                                        <v-alert :value="true" class="bg-warning" icon="warning">
                                                          Rezervacija tuščia, arba įvyko klaida kraunant duomenis iš duombazės...
                                                        </v-alert>
                                                      </template>
                                                    </v-data-table>
                                                </v-card-text>
                                                <div class="card-body mt-1 border border-dark" v-else>
                                                  <div class="text-center h5 pa-2">
                                                    Objekte įrankių nėra...
                                                  </div>
                                                </div>
                                            </v-container>
                                        </v-layout>
                                        <v-layout justify-end v-if="$user.UserRole =='Administratorius'">
                                          <v-flex shrink>
                                            <v-btn outline color="primary" @click="closeObject(object)">
                                              <v-icon class="px-2">fa-flag-checkered</v-icon>
                                              Uždaryti objektą
                                            </v-btn>
                                          </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-card-text>
                <div class="card-body mt-1 border border-dark" v-else-if="objects.length == 0">
                  <div class="text-center h5 pa-5">
                    Objektų nėra...
                  </div>
                </div>
        </v-layout>
    </v-container>
</template>
<script>
import AddObject from '../modals/AddObject.vue'
import AssingForeman from '../modals/object/AssignForeman.vue'
import swal from 'sweetalert'
import renttime from '../../mixins/renttime'
import consumables from '../../mixins/consumables'
export default{
    mixins: [renttime, consumables],
    data(){
        return {
            objects: null,
            headers: [
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
                  text: 'Kiekis (vnt.)',
                  align: 'center',
                  value: 'ItemWithdrawalQuantity'
                },
                {
                  text: 'Išdavimo data',
                  value: 'created_at',
                  align: 'left'
                }
              ],
        }
    },
    mounted(){
        this.loadObjects();
    },
    computed: {

    },
    methods: {
        loadObjects: function(){
            this.$http.get('object/list').then((response)=>{
                if(response.status == 200){
                    this.objects = response.data
                    this.proccessObjectWithdrawals()
                    this.$contentLoadingHide()
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        },
        removeForeman: function(user, objectID){
            swal({
              title: 'Ar tikrai norite pašalinti '+user.Username+' iš šio objekto?',
              text: 'Pašalinus vartotoją iš objekto, vartotojo įrankiai ir toliau bus priskirti tiek objektui tiek vartotojui.',
              icon: 'warning',
              dangerMode: true,
              buttons: {
                del: { text: 'Trinti', value: true},
                cancel: {text: 'Atšaukti'}
              }
            }).then(value => {
              if(value){
                this.$http.post('object/foreman/remove', {
                  userID: user.UserID,
                  objectID: objectID
                }).then((response)=>{
                    if(response.status == 200){
                        swal(response.data.message, response.data.success, "success").then(value => { this.loadObjects()})
                    }
                }).catch(error =>{
                    if(error.response.status == 422)
                    {
                        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                    }
                    else {
                      swal('Klaida', error.response.data.message, 'error')
                    }
                })
              }
            })
        },
        closeObject: function(object){
            swal({
              title: `Ar tikrai norite uždaryti ${object.ObjectName} objektą?`,
              text: 'Uždarius objektą, jam daugiau nebus galima priskirti įrankių, tačiau bus galima ten esamus įrankius grąžinti.',
              icon: 'warning',
              dangerMode: true,
              buttons: {
                close: { text: 'Uždaryti', value: true},
                cancel: {text: 'Atšaukti'}
              }
            }).then(value => {
              if(value){
                this.$http.get('object/close/'+object.ObjectID).then((response)=>{
                    if(response.status == 200){
                        swal(response.data.message, response.data.success, "success").then(value => {
                          this.$router.push({name: 'objectItems', params: {objectID: object.ObjectID}})
                        })
                    }
                }).catch(error =>{
                    if(error.response.status == 422)
                    {
                        swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                    }
                    else {
                      swal('Klaida', error.response.data.message, 'error')
                    }
                })
              }
            })
        },
        show: function(name, param = {}){
            this.$modal.show(name, param)
        },
        days: function(date){
            var dateRented = new Date(date).getTime()
            var currentDate = new Date().getTime()
            if(dateRented > currentDate)
                return 0
            var timeDiff = Math.abs(currentDate - dateRented);
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            return diffDays+1
        },
    },
    components: {
        AddObject,
        AssingForeman
    }
}
</script>
