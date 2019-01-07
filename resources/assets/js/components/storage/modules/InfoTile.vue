<template>
  <v-card tile class="border d-flex align-items-stretch flex-column flex-wrap">
    <v-card-title class="justify-center">
      <v-icon x-large :color="tile_icon_color">
        {{tile_icon}}
      </v-icon>
    </v-card-title>
    <v-card-text>
      <div class="text-center">
        {{tile_text}}
      </div>
      <div class="display-1 primary--text text-center">
        {{tile_number}}
      </div>
    </v-card-text>
  </v-card>
</template>
<script>
export default{
  props: {
    icon: {
      required: true,
      type: String
    },
    color: {
      required: true,
      type: String
    },
    text: {
      required: true,
      type: String
    },
    number: {
      required: true,
      type: String
    }
  },
  data(){
    return {
      tile_icon: this.icon,
      tile_icon_color: this.color,
      tile_text: this.text,
      tile_number: this.number
    };
  }
}
</script>
