<template>
    <v-container>
        <create-modal></create-modal>
        <rename-modal></rename-modal>
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
                <v-expansion-panel-content class="primary v-toolbar mb-1 text-white"
                        v-for="(storage,i) in storages"
                        :key="i"
                >
                    <div slot="header" class="h5">
                        {{storage.name}}
                    </div>
                    <v-icon slot="actions" color="white">$vuetify.icons.expand</v-icon>
                    <v-card>
                        <v-card-text class="bg-white">
                            <div class="container">
                                <div class="row equal mb-lg-5 mb-2">
                                    <div class="col-sm-3 col-6 px-1 d-flex">
                                        <infoTile icon="fa-toolbox" color="success" text="Įrankiai"
                                                  number="85"></infoTile>
                                    </div>
                                    <div class="col-sm-3 col-6 px-1 d-flex">
                                        <infoTile icon="fa-toolbox" color="warning" text="Naudojami įrankiai"
                                                  number="52"></infoTile>
                                    </div>
                                    <div class="col-sm-3 col-6 px-1 d-flex">
                                        <infoTile icon="fa-wrench" color="primary" text="Taisomi įrankiai"
                                                  number="5"></infoTile>
                                    </div>
                                    <div class="col-sm-3 col-6 px-1 d-flex">
                                        <infoTile icon="fa-flag" color="error" text="Laukia patvirtinimo"
                                                  number="3"></infoTile>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6 px-1 d-flex">
                                        <managerList :managers="storage.managers"></managerList>
                                    </div>
                                    <div class="col-12 col-sm-6 px-1 d-flex justify-content-center">
                                        <buttonBox :storage="storage"></buttonBox>
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
    import renameModal from '../modals/storage/rename.vue';
    import infoTile from './modules/InfoTile.vue';
    import managerList from './modules/ManagersList.vue';
    import buttonBox from './modules/ButtonBox.vue';
    export default {
        name: "Storage",
        created() {
            this.$contentLoadingHide();
            this.$eventBus.$on('rename_storage_modal', (payload) => {
               this.$modal.show('rename-storage-modal', payload);
            });
        },
        computed: {
            storages() {
                return this.$store.state.storage.storageList;
            }
        },
        components: {
            createModal,
            renameModal,
            infoTile,
            managerList,
            buttonBox
        }
    }
</script>
