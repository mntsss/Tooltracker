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
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</v-card-text>
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
