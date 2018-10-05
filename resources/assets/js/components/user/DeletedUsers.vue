<template>
        <div class="container" style="min-height: 70vh !important">
            <RestoreUser></RestoreUser>
            <div class="card" v-if="users">
                <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 secondary v-toolbar mx-0">
                    <v-flex headline align-center>
                        <div class="text-center mb-0 pa-1">
                            Ištrinti vartotojai
                        </div>
                    </v-flex>
                </v-layout>
                <v-container class="card-body" v-if="users.length > 0">
                    <v-layout row mx-0 wrap align-center>
                        <v-expansion-panel>
                            <v-expansion-panel-content class="primary v-toolbar mb-1 text-white" v-for="(user, i) in users" :key="i">
                                <div slot="header">
                                    {{user.Username}}
                                </div>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-phone</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>Telefonas:</v-flex>
                                            <v-flex px-2>{{user.UserPhone}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-envelope</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>El. paštas:</v-flex>
                                            <v-flex px-2>{{user.email}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-crown</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Vartotojo tipas:</v-flex>
                                            <v-flex px-2>{{user.UserRole}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-trash</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Ištrynimo data:</v-flex>
                                            <v-flex px-2>{{user.updated_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-calendar-check</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Registracijos data:</v-flex>
                                            <v-flex px-2>{{user.created_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="primary--text">fa-address-card</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Priskirta identifikacinė kortelė:</v-flex>
                                            <v-flex px-2>
                                              <v-icon headline class="text-success" v-if="user.UserRFIDCode">fa-check</v-icon>
                                              <v-icon headline class="text-warning" v-else-if="user.UserRFIDCode == null">fa-times</v-icon>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row mx-0 wrap align-center pa-2 justify-end v-if="$user.UserRole == 'Administratorius'">
                                            <v-flex shrink justify-end>
                                              <v-btn outline>
                                                  <v-icon class="primary--text">fa-history</v-icon>
                                                  <span class="mx-2">Istorija</span>
                                              </v-btn>
                                              <v-btn outline>
                                                  <v-icon class="primary--text">fa-toolbox</v-icon>
                                                  <span class="mx-2">Įrankiai</span>
                                              </v-btn>
                                              <v-btn outline @click="show('restore-user-modal', {id: user.UserID})">
                                                  <v-icon class="primary--text">fa-undo</v-icon>
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
                <div class="card-body mt-1 border border-dark" v-else-if="users.length == 0">
                  <div class="text-center h5 pa-5">
                    Ištrintų vartotojų nėra...
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
                    this.$contentLoadingHide()
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        }
    },
    components: {
        RestoreUser
    }
}
</script>
