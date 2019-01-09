<template>
    <div class="btn-container--width-300">
        <v-btn class="w-100 mx-0" outline color="primary" @click="items()">
            <v-icon class="px-2">fa-toolbox</v-icon>
            Įrankiai
        </v-btn>
        <v-btn class="w-100 mx-0" outline color="primary" @click="closeObject(object)">
            <v-icon class="px-2">fa-history</v-icon>
            Istorija
        </v-btn>
        <v-btn class="w-100 mx-0" outline color="primary" @click="renameStorage()">
            <v-icon class="px-2">fa-pen</v-icon>
            Pervadinti sandėlį
        </v-btn>
        <v-btn class="w-100 mx-0" outline color="primary" @click="closeObject(object)">
            <v-icon class="px-2">fa-trash</v-icon>
            Panaikinti sandėlį
        </v-btn>
    </div>
</template>

<script>
    export default {
        name: "buttonsBox",
        props: {
            storage: {
                required: true,
                type: Object
            }
        },
        methods: {
            renameStorage: function(){
                this.$eventBus.$emit('rename_storage_modal', {id: this.storage.id, name: this.storage.name});
            },
            items: function(){
              this.$store.dispatch('storage/LOAD_STORAGE', {id: this.storage.id});
              this.$router.push({name: 'groups', params: {storage_id: this.storage.id}});
            }
        }
    }
</script>

<style scoped>
    .btn-container--width-300{
        width: 300px;
        display: block;
        position: relative;
        max-width: 300px;
    }
</style>
