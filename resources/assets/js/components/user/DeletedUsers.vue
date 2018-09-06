<template>
    <div class="loading-parent">
      <RestoreUser></RestoreUser>
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <div class="container" style="min-height: 70vh !important">
            <div class="card">
                <v-layout row wrap align-center class="card-header pb-0 pt-0 theme--dark v-toolbar mx-0">
                    <v-flex headline align-center>
                        <div class="text-center mb-0">
                            Ištrinti vartotojai
                        </div>
                    </v-flex>
                </v-layout>
                <v-container class="card-body" v-if="users.length > 0">
                    <v-layout row wrap align-center>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(user, i) in users" :key="i">
                                <div slot="header">
                                    {{user.Username}}
                                </div>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-phone</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>Telefonas:</v-flex>
                                            <v-flex px-2>{{user.UserPhone}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-envelope</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>El. paštas:</v-flex>
                                            <v-flex px-2>{{user.email}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-crown</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Vartotojo tipas:</v-flex>
                                            <v-flex px-2>{{user.UserRole}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-trash</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Ištrynimo data:</v-flex>
                                            <v-flex px-2>{{user.updated_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-calendar-check</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Registracijos data:</v-flex>
                                            <v-flex px-2>{{user.created_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-address-card</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Priskirta identifikacinė kortelė:</v-flex>
                                            <v-flex px-2>
                                              <v-icon headline class="text-success" v-if="user.UserRFIDCode">fa-check</v-icon>
                                              <v-icon headline class="text-warning" v-else-if="user.UserRFIDCode == null">fa-times</v-icon>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center pa-2 justify-end>
                                            <v-flex shrink justify-end>
                                              <v-btn outline>
                                                  <v-icon class="text-danger">fa-history</v-icon>
                                                  <span class="mx-2">Istorija</span>
                                              </v-btn>
                                              <v-btn outline>
                                                  <v-icon class="text-danger">fa-toolbox</v-icon>
                                                  <span class="mx-2">Įrankiai</span>
                                              </v-btn>
                                              <v-btn outline @click="show('restore-user-modal', {id: user.UserID})">
                                                  <v-icon class="text-danger">fa-undo</v-icon>
                                                  <span class="mx-2">Atkurti</span>
                                              </v-btn>

                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-container>
                <div class="card-body bg-dark mt-1 border border-dark" v-else-if="users.length == 0">
                  <div class="text-center text-light h5 pa-5">
                    Ištrintų vartotojų nėra...
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import Loading from 'vue-loading-overlay'

import RestoreUser from '../modals/RestoreDeletedUser.vue'

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
            this.$http.get('user/deleted').then((response)=>{
                if(response.status == 200){
                    this.users = response.data
                    this.isLoading = false
                }
            }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], 'error')
            })
        }
    },
    components: {
        Loading,
        RestoreUser
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
