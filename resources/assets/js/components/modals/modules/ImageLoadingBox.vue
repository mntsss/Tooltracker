<template>
  <v-dialog v-model="visible" hide-overlay persistent width="300">
    <v-card class="border border-danger">
      <v-card-text>
        Kraunama...
        <v-progress-linear indeterminate color="primary" class="mb-0"></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
export default{
  data(){
    return {
      visible: false
    }
  },
  mounted(){
    this.$eventBus.$on('image_resize_started', () => {
      this.visible = true;
    });
    this.$eventBus.$on('image_resize_finished', () => {
      this.visible = false;
    });
  }
}
</script>
