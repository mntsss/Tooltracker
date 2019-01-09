<template>
    <div class="card">
        <div class="card-header py-0">
            <div class="text-center h5 row mb-0 align-items-center">
                <div class="flex text-center">
                    Sandėlio vadybininkai
                </div>
                <div class="flex shrink" v-if="$user.UserRole == 'Administratorius'">
                    <a @click="$modal.show('add-storage-modal')" class="headline"><span
                            class="fas fa-plus primary--text p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></span></a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <v-list>
                <v-list-tile
                        v-for="manager in managersList"
                        :key="manager.id"
                        avatar
                        @click=""
                >
                    <v-list-tile-content>
                        <v-list-tile-title>{{ manager.Username }}</v-list-tile-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-btn icon ripple>
                            <v-icon color="error">fa-times</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
        </div>
    </div>

</template>

<script>
    export default {
        name: "ManagersList",
        props:{
            managers: {
                required: true,
                type: Array
            }
        },
        computed: {
            managersList: function(){
                return this.managers;
            }
        }
    }
</script>