<template>
    <Modal modal_name="add-storage-modal">
        <span slot="header">
            Pridėti sandėlį
        </span>
        <v-form slot="content">
            <v-text-field v-model="name" prepend-icon="fa-warehouse" :max = "max" required name="name" label="Pavadinimas"></v-text-field>
            <v-btn v-on:click="" :disabled="!valid">Pridėti</v-btn>
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
                max: 40
            }
        },
        computed:{
            valid(){
                return this.name.length > 3;
            }
        },
        components:
            {
                Modal
            }
    }
</script>
