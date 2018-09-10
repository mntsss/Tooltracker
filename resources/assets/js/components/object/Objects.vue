<template>
    <div class="loading-parent">
      <AddObject></AddObject>
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
    <v-container>
        <v-layout row v-if="objects">
                <v-layout row mb-3 class="theme--dark v-toolbar">
                    <v-flex headline align-center>
                        <div class="text-center mb-0">
                            Objektai
                        </div>
                    </v-flex>
                    <v-flex shrink>
                        <a @click="show('add-object-modal')" class="headline"><span class="fas fa-plus text-danger p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
                    </v-flex>
                </v-layout>
                <v-card-text v-if="objects.length > 0">
                    <v-layout row mx-0>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(object, i) in objects" :key="i">
                                <div slot="header">
                                    <span class="h5">{{object.ObjectName}}</span>
                                </div>
                                <v-card>
                                    <v-card-text>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-user-tie</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Darbų vygdytojas:</v-flex>
                                            <v-flex px-2>{{object.user.Username}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center >
                                            <v-flex shrink pa-2 style="width: 40px !important">
                                                <v-icon headline class="text-danger">fa-calendar-plus</v-icon>
                                            </v-flex>
                                            <v-flex px-2 shrink>Objektas pridėtas:</v-flex>
                                            <v-flex px-2>{{object.created_at}}</v-flex>
                                        </v-layout>
                                        <v-layout row wrap align-center justify-center>
                                            <v-container>
                                                <v-card-title class="theme--dark v-toolbar mx-auto ">
                                                    <h5>Naudojami įrankiai / išdavimo data</h5>
                                                </v-card-title>
                                                <v-card-text>
                                                    <router-link tag="div" class="row remove-side-margin cursor-pointer h6" :to="{ name: 'item', params: { itemProp: {item: withdrawal.item, state: 'Naudojamas'}}}" v-for="(withdrawal, i) in object.item_withdrawals" :key="i">
                                                      <div class="col-6 h6">
                                                        {{withdrawal.item.ItemName}}
                                                      </div>
                                                      <div class="col text-center h6">
                                                        {{withdrawal.created_at}}
                                                      </div>
                                                    </router-link>
                                                </v-card-text>
                                            </v-container>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-card-text>
                <div class="card-body bg-dark mt-1 border border-dark" v-else-if="objects.length == 0">
                  <div class="text-center text-light h5 pa-5">
                    Objektų nėra...
                  </div>
                </div>
        </v-layout>
    </v-container>
</div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import AddObject from '../modals/AddObject.vue'

import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'

export default{
    data(){
        return {
            objects: null,
            isLoading: true,
            fullPage: false
        }
    },
    created(){

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
                    this.isLoading = false
                }
            }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], 'error')
            })
        },
        show: function(name, param = {}){
            this.$modal.show(name, param)
        }
    },
    components: {
        Loading,
        AddObject
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
