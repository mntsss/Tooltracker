<template>
    <Modal modal_name="rename-storage-modal" v-on:closed = "clear()">
        <span slot="header">
            Pervadinti sandėlį
        </span>
        <v-form slot="content">
            <v-text-field v-model="name" prepend-icon="fa-warehouse" :max = "max" required name="name" label="Pavadinimas"></v-text-field>
            <v-btn @click="save()" :disabled="!valid">Pervadinti</v-btn>
        </v-form>
    </Modal>
</template>

<script>
    import Modal from './../Modal.vue';
    export default {
        name: "create",
        data(){
            return {
                name: "",
                id: null,
                max: 40
            }
        },
        mounted(){
          this.$eventBus.$on('rename_storage_modal', (payload) => {
              this.name = payload.name;
              this.id = payload.id;
              this.$modal.show('rename-storage-modal');
          });
        },
        computed:{
            valid(){
                return this.name.length > 3;
            }
        },
        methods:{
          save: function(){
            const data = {name: this.name, id: this.id};
            this.$store.dispatch('storage/RENAME_STORAGE', {data});
            this.$modal.hide('rename-storage-modal');
          },
          clear: function(){
            this.name = "";
            this.id = null;
          }
        },
        components:
            {
                Modal
            }
    }
</script>
