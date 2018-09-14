<template>
    <div class="loading-parent">
      <CreateUser></CreateUser>
      <EditUser></EditUser>
      <AddUserCard></AddUserCard>
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <div class="container" style="min-height: 70vh !important">
            <div class="card" v-if="users">
                <v-layout row wrap align-center class="card-header pb-0 pt-0 mb-0 secondary v-toolbar mx-0">
                    <v-flex headline align-center>
                        <div class="text-center mb-0">
                            Vartotojai
                        </div>
                    </v-flex>
                    <v-flex shrink>
                        <a @click="show('create-user-modal')" class="headline"><span class="fas fa-plus primary--text p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
                    </v-flex>
                </v-layout>
                <v-container class="card-body mt-0 pt-4" v-if="users.length > 0">
                    <v-layout row wrap align-center>
                        <v-expansion-panel>
                            <v-expansion-panel-content class="primary v-toolbar mb-1 text-white" v-for="(user, i) in users" :key="i">
                                <div slot="header">
                                    {{user.Username}}
                                </div>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-phone</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>Telefonas:</v-flex>
                                            <v-flex px-2>{{user.UserPhone}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-envelope</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>El. paštas:</v-flex>
                                            <v-flex px-2>{{user.email}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-crown</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Vartotojo tipas:</v-flex>
                                            <v-flex px-2>{{user.UserRole}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-calendar-alt</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Paskutinis aktyvumas:</v-flex>
                                            <v-flex px-2>{{user.UserLastSeen}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-calendar-check</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Registracijos data:</v-flex>
                                            <v-flex px-2>{{user.created_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-address-card</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Priskirta identifikacinė kortelė:</v-flex>
                                            <v-flex px-2>
                                              <v-icon headline class="text-success" v-if="user.UserRFIDCode">fa-check</v-icon>
                                              <v-icon headline class="text-warning" v-else-if="user.UserRFIDCode == null">fa-times</v-icon>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-address-card</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Vartotojo identifikacinis numeris:</v-flex>
                                            <v-flex px-2>
                                              {{user.UserID}}
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center pa-2 justify-end>
                                            <v-flex shrink justify-end>
                                              <v-btn outline>
                                                  <v-icon class="primary--text">fa-history</v-icon>
                                                  <span class="mx-2">Istorija</span>
                                              </v-btn>
                                              <v-btn outline @click="$router.push({name:'userWithdrawals', params: {userID: user.UserID}})">
                                                  <v-icon class="primary--text">fa-toolbox</v-icon>
                                                  <span class="mx-2">Įrankiai</span>
                                              </v-btn>
                                              <v-btn outline @click="show('edit-user-modal', {user: user})">
                                                    <v-icon class="primary--text">fa-edit</v-icon>
                                                    <span class="mx-2">Redaguoti</span>
                                                </v-btn>
                                                <v-btn outline @click="show('add-user-card-modal', {id: user.UserID})">
                                                    <v-icon class="primary--text">fa-id-card</v-icon>
                                                    <span class="mx-2">Nauja kortelė</span>
                                                </v-btn>
                                              <v-btn outline @click="deleteUser(user)">
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
                </v-container>
                <div class="card-body mt-1 border border-dark" v-else-if="users.length == 0">
                  <div class="text-center h5 pa-5">
                    Vartotojų nėra...
                  </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import CreateUser from '../modals/CreateUser.vue'
import EditUser from '../modals/EditUser.vue'
import AddUserCard from '../modals/AddUserCard.vue'

import Loading from 'vue-loading-overlay'

import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default{

    data(){
        return {
            isLoading: true,
            fullPage: false,
            users: null
        }
    },
    created(){
        this.loadUsers()
    },
    methods: {
        show: function(name, param = {}){
            this.$modal.show(name, param)
        },
        loadUsers: function(){
            this.$http.get('user/list').then((response)=>{
                if(response.status == 200){
                    this.users = response.data
                    this.isLoading = false
                }
            }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], 'error')
            })
        },
        deleteUser: function(user){
            swal({
              title: 'Ar tikrai norite ištrinti vartotoją '+user.Username+'?',
              text: 'Ištrinti vartotoją galima tik tuo atveju, jeigu jis neturi aktyvių rezervacijų, paimtų ar įšaldytų įrankių.',
              icon: 'warning',
              dangerMode: true,
              buttons: {
                del: { text: 'Trinti', value: true},
                cancel: {text: 'Atšaukti'}
              }
            }).then(value => {
              if(value){
                this.$http.get('/user/delete/'+user.UserID).then((response)=>{
                    if(response.status == 200){
                        swal(response.data.message, response.data.success, "success").then(value => { this.loadUsers()})
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
        }
    },
    components: {
        Loading,
        CreateUser,
        EditUser,
        AddUserCard
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
