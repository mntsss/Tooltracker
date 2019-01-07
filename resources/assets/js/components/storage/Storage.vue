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
    </v-container>
</template>

<script>
    import createModal from '../modals/storage/create.vue';
    export default {
        name: "Storage",
        created(){
            this.$contentLoadingHide();
        },
        components: {
            createModal
        }
    }
</script>