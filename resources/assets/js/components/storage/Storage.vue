<template>
    <v-container>
        <create-modal></create-modal>
        <v-layout row mx-0 mb-3 class="secondary v-toolbar">
            <v-flex headline align-center>
                <div class="text-center mb-0">
                    Sandėliai
                </div>
            </v-flex>
            <v-flex shrink v-if="$user.UserRole == 'Administratorius'">
                <a @click="$modal.show('add-storage-modal')" class="headline"><span
                        class="fas fa-plus primary--text p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
            </v-flex>
        </v-layout>
        <v-layout>
          <v-expansion-panel>
             <v-expansion-panel-content
               v-for="(storage,i) in storages"
               :key="i"
             >
               <div slot="header" class="h5">
                   {{storage.name}}
               </div>
               <v-card>
                 <v-card-text>
                   <div class="container">
                     <div class="row equal">
                       <div class="col-sm-3 col-6 px-1 d-flex">
                         <infoTile icon="fa-toolbox" color="success" text="Įrankiai" number="85"></infoTile>
                       </div>
                       <div class="col-sm-3 col-6 px-1 d-flex">
                         <infoTile icon="fa-toolbox" color="warning" text="Naudojami įrankiai" number="52"></infoTile>
                       </div>
                       <div class="col-sm-3 col-6 px-1 d-flex">
                         <infoTile icon="fa-wrench" color="primary" text="Taisomi įrankiai" number="5"></infoTile>
                       </div>
                       <div class="col-sm-3 col-6 px-1 d-flex">
                         <infoTile icon="fa-flag" color="danger" text="Laukia patvirtinimo" number="3"></infoTile>
                       </div>
                     </div>
                   </div>
                   <div class="row" v-if="storage.managers.length > 0">
                     <div class="card">
                       <div class="card-header text-center">
                         Sandėlio vadybininkai
                       </div>
                       <div class="card-body">
                         <div class="row mx-0" v-for="(manager, i) in storage.managers" :key = "i">
                           <div class="h5 px-2 border border-primary">
                             {{manager.Username}}
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                 </v-card-text>
               </v-card>
             </v-expansion-panel-content>
           </v-expansion-panel>
        </v-layout>
    </v-container>
</template>

<script>
    import createModal from '../modals/storage/create.vue';
    import infoTile from './modules/InfoTile.vue';
    export default {
        name: "Storage",
        created(){
            this.$contentLoadingHide();
        },
        computed:{
          storages(){
            return this.$store.state.storage.storageList;
          }
        },
        components: {
            createModal,
            infoTile
        }
    }
</script>
