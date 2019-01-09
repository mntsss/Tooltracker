<template>
  <Modal modal_name="create-group-modal" v-on:closed="clear()">
    <span slot="header">
      Pridėti įrankių grupę
    </span>
    <div slot="content" class="">
      <v-layout>
        <v-text-field v-model="name" prepend-icon="fa-toolbox" :max = "max" required name="name" label="Pavadinimas"></v-text-field>
      </v-layout>
      <ImageUpload></ImageUpload>
      <v-layout>
        <v-btn @click="save()" :disabled="!valid">Pridėti</v-btn>
      </v-layout>
    </div>
  </Modal>
</template>
<script>
import Modal from './../Modal.vue';
import ImageUpload from './../modules/ImageUpload.vue'
export default {
  data(){
    return{
      name: "",
      max: 60,
      image: null
    }
  },
  computed: {
    valid: function(){
      return this.name.length > 3;
    }
  },
  mounted(){
    this.$eventBus.$on('image-uploaded', (image) => {
      this.image = image;
    });
  },
  methods: {
    clear: function(){
      this.name = "";
      this.image = null;
    },
    save: function(){
      const data = {name: this.name, image: this.image, storage_id: this.$store.state.storage.storage.id};
      this.$store.dispatch("storage/CREATE_GROUP", {data});
      this.$modal.hide("create-group-modal");
    }
  },
  components: {
    Modal,
    ImageUpload,
  }
}
</script>
