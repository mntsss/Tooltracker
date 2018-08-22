<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <div class="container" style="min-height: 70vh !important">
            <div class="card">
                <v-layout row wrap align-center class="card-header pb-0 pt-0">
                    <v-flex headline align-center>
                        <div class="text-center mb-0 text-dark">
                            Vartotojai
                        </div>
                    </v-flex>
                    <v-flex shrink>
                        <a @click="show('create-user-modal')" class="headline"><span class="fas fa-plus text-primary p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
                    </v-flex>
                </v-layout>
                <v-container class="card-body">
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
                                                <v-icon headline>fa-phone</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>Telefonas:</v-flex>
                                            <v-flex px-2>{{user.UserPhone}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline>fa-envelope</v-icon>
                                            </v-flex>
                                            <v-flex shrink px-2>El. paštas:</v-flex>
                                            <v-flex px-2>{{user.email}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline>fa-crown</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Vartotojo tipas:</v-flex>
                                            <v-flex px-2>{{user.UserRole}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline>fa-calendar-alt</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Paskutinis aktyvumas:</v-flex>
                                            <v-flex px-2>{{user.UserLastSeen}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center pa-2 justify-end>
                                            <v-flex shrink justify-end>
                                                <v-btn outline>
                                                    <v-icon>fa-history</v-icon>
                                                    <span class="mx-2">Istorija</span>
                                                </v-btn>
                                                <v-btn outline>
                                                    <v-icon>fa-toolbox</v-icon>
                                                    <span class="mx-2">Įrankiai</span>
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-container>
            </div>

        </div>
    </div>
</template>
<script>
import CreateUser from './modals/CreateUser.vue'
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
        }
    },
    components: {
        Loading,
        CreateUser
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
